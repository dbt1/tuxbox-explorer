<?php
/**
 * index.php
 *
 * MIT License
 *
 * Copyright (C) 2025 Thilo Graf
 *
 * This script provides a PHP-based file and directory explorer with:
 *  - wildcard-based ignore lists (for files and directories)
 *  - optional aliases for directory names
 *  - an allowlist (whitelist) for files and directories
 *  - a responsive layout with optional logo
 *  - various sort criteria (name, extension, date)
 *  - filtering with wildcards (e.g. "*.log" or "File*")
 *  - pagination to browse entries by pages
 *  - a sticky/fixed header layout so that only the file list scrolls
 *  - an info about the total number of entries
 *
 * System Requirements:
 *  - PHP >= 7.4
 *    (Note: if using str_contains(), you need PHP >= 8.0 or replace with strpos())
 *  - Webserver (Apache, Nginx, etc.) with PHP
 *  - Write/Read permission on target directory (depending on use case)
 *  - config.php must exist (or you rename config-sample.php to config.php)
 */

// Uncomment for debugging (remove on production)
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

 /* --------------------------------------------------------
    0) CHECK FOR CONFIG FILE
    -------------------------------------------------------- */
$configPath = __DIR__ . '/config/config.php';
if (!file_exists($configPath)) {
    // Simple HTML response if config is missing
    header('Content-Type: text/html; charset=utf-8');
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Configuration missing</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #2b2e38;
                color:rgb(233, 174, 12);
                margin: 0;
                padding: 0;
            }
            .config-error {
                max-width: 600px;
                margin: 80px auto;
                background: #242731;
                padding: 20px;
                border: 1px solid #444;
                border-radius: 6px;
            }
            .config-error h1 {
                margin: 0 0 15px;
                font-size: 1.4rem;
                color: #fff;
            }
            .config-error p {
                line-height: 1.5;
            }
        </style>
    </head>
    <body>
        <div class='config-error'>
            <h1>No Configuration File Found!</h1>
            <p>The file <code>config/config.php</code> is missing.</p>
            <p>Please rename or copy <code>config-sample.php</code> to <code>config.php</code>
            and adjust it to your needs.</p>
            <p>Without this configuration file, the script cannot run.
            </p>
        </div>
    </body>
    </html>";
    exit;
}

// Script version
$APP_VERSION="0.1.17";

// ---------------------------
// 1) LOAD CONFIG
// ---------------------------
require_once $configPath;

/**
 * Decide which path to use as the "root" for the explorer:
 * If $FILES_DIRECTORY is set and valid, we use that. Otherwise we fall back to $ROOT_PATH.
 */
$EXPLORER_PATH = ($FILES_DIRECTORY && is_dir($FILES_DIRECTORY))
    ? $FILES_DIRECTORY
    : $ROOT_PATH;

/* --------------------------------------------------------
   2) HELPER FUNCTIONS
   -------------------------------------------------------- */

/**
 * buildSafePath:
 * Ensures the requested path is inside ROOT_PATH (or EXPLORER_PATH in this case).
 *
 * @param string $rootPath Absolute path to the root directory
 * @param string $subPath  Requested subpath (relative or partial path)
 * @return string|null     Returns the real path if valid, otherwise null
 */
function buildSafePath($rootPath, $subPath) {
    // always get the real absolute root path
    $rootPath = rtrim(realpath($rootPath), DIRECTORY_SEPARATOR);

    // sanitize the subPath
    $subPath  = str_replace("\0", '', $subPath);
    $subPath  = str_replace('\\', '/', $subPath);
    $subPath  = preg_replace('/\.\.+/', '', $subPath);

    $target     = $rootPath . DIRECTORY_SEPARATOR . $subPath;
    $realTarget = realpath($target);

    // check if realTarget is valid and inside rootPath
    if ($realTarget !== false && strpos($realTarget, $rootPath) === 0) {
        return $realTarget;
    }
    return null;
}

/**
 * isEmptyDirectory:
 * checks if directory has no files except . and ..
 *
 * @param string $path
 * @return bool
 */
function isEmptyDirectory($path) {
    if (!is_dir($path)) {
        return false;
    }
    $files = array_diff(scandir($path), ['.', '..']);
    return empty($files);
}

/**
 * getAliasOrOriginal:
 * returns an alias for the directory if specified; otherwise the original name
 *
 * @param string $dirName
 * @param array  $aliases
 * @return string
 */
function getAliasOrOriginal($dirName, array $aliases) {
    return array_key_exists($dirName, $aliases) ? $aliases[$dirName] : $dirName;
}

/**
 * shouldIgnore:
 * decides if a file/dir should be ignored based on ignore-lists and allowlists
 *
 * @param string $entry
 * @param array  $ignoreDirs
 * @param array  $ignoreFiles
 * @param array  $allowDirs
 * @param array  $allowFiles
 * @param string $fullPath
 * @return bool  True if ignored, false otherwise
 */
function shouldIgnore($entry, array $ignoreDirs, array $ignoreFiles, array $allowDirs, array $allowFiles, $fullPath) {
    // 1) check allowlists first
    if (is_dir($fullPath)) {
        foreach ($allowDirs as $allow) {
            if (fnmatch($allow, $entry, FNM_CASEFOLD)) {
                return false;
            }
        }
    } else {
        foreach ($allowFiles as $allow) {
            if (fnmatch($allow, $entry, FNM_CASEFOLD)) {
                return false;
            }
        }
    }
    // 2) check ignore patterns
    if (is_dir($fullPath)) {
        foreach ($ignoreDirs as $pattern) {
            if (fnmatch($pattern, $entry, FNM_CASEFOLD)) {
                return true;
            }
        }
    } else {
        foreach ($ignoreFiles as $pattern) {
            if (fnmatch($pattern, $entry, FNM_CASEFOLD)) {
                return true;
            }
        }
    }
    return false;
}

/**
 * sortEntries:
 * supports multiple modes: nameAsc/nameDesc, extAsc/extDesc, dateAsc/dateDesc
 * directories always come before files
 *
 * @param array  $entries
 * @param string $sortMode
 * @return array
 */
function sortEntries(array $entries, $sortMode = 'nameAsc') {
    usort($entries, function($a, $b) use ($sortMode) {
        // always list directories first
        if ($a['isDir'] && !$b['isDir']) return -1;
        if (!$a['isDir'] && $b['isDir']) return 1;

        switch($sortMode) {
            case 'nameAsc':
                return strcasecmp($a['name'], $b['name']);
            case 'nameDesc':
                return strcasecmp($b['name'], $a['name']);
            case 'extAsc':
                $extA = pathinfo($a['name'], PATHINFO_EXTENSION);
                $extB = pathinfo($b['name'], PATHINFO_EXTENSION);
                return strcasecmp($extA, $extB);
            case 'extDesc':
                $extA = pathinfo($a['name'], PATHINFO_EXTENSION);
                $extB = pathinfo($b['name'], PATHINFO_EXTENSION);
                return strcasecmp($extB, $extA);
            case 'dateAsc':
                // oldest first
                $timeA = filemtime($a['path']);
                $timeB = filemtime($b['path']);
                return $timeA <=> $timeB;
            case 'dateDesc':
                // newest first
                $timeA = filemtime($a['path']);
                $timeB = filemtime($b['path']);
                return $timeB <=> $timeA;
            default:
                // fallback -> nameAsc
                return strcasecmp($a['name'], $b['name']);
        }
    });
    return $entries;
}

/**
 * wildcardMatch:
 * matches a string against a wildcard pattern (like '*abc*').
 * if no '*' is present, we interpret as "*pattern*" (partial).
 *
 * @param string $pattern
 * @param string $string
 * @return bool
 */
function wildcardMatch($pattern, $string) {
    // if no '*' => treat as "*pattern*"
    if (strpos($pattern, '*') === false) {
        $pattern = '*' . $pattern . '*';
    }
    // escape regex special chars (except '*')
    $pattern = preg_quote($pattern, '/');
    // convert \* to .*
    $pattern = str_replace('\*', '.*', $pattern);
    // case-insensitive check
    return (bool)preg_match('/^' . $pattern . '$/i', $string);
}

/**
 * filterEntries:
 * if $filter is empty, return all. otherwise, only items that match wildcard
 *
 * @param array  $entries
 * @param string $filter
 * @return array
 */
function filterEntries(array $entries, $filter = '') {
    if (empty($filter)) {
        return $entries;
    }
    $filtered = [];
    foreach ($entries as $item) {
        if (wildcardMatch($filter, $item['name'])) {
            $filtered[] = $item;
        }
    }
    return $filtered;
}

/**
 * listItems:
 * collects directory entries, filters, sorts, and then slices them based on pagination
 *
 * @param string $path
 * @param array  $ignoreDirs
 * @param array  $ignoreFiles
 * @param string $sortMode
 * @param string $filter
 * @param int    $page         current page number (1-based)
 * @param int    $itemsPerPage number of entries per page
 * @return array  {
 *   'html'        => string of <li> items,
 *   'totalCount'  => int total number of matching entries (before pagination),
 *   'page'        => int current page number,
 *   'totalPages'  => int total number of pages
 * }
 */
function listItems($path, array $ignoreDirs, array $ignoreFiles, $sortMode, $filter, $page = 1, $itemsPerPage = 20) {
    // We'll continue using the global config arrays, but for root references we use $EXPLORER_PATH now
    global $EXPLORER_PATH, $DIRECTORY_ALIASES, $ALLOW_DIR_PATTERNS, $ALLOW_FILE_PATTERNS;

    $output        = [];
    $entriesForSort= [];
    $rootRealPath  = realpath($EXPLORER_PATH);
    $rootLen       = strlen($rootRealPath);

    if ($handle = opendir($path)) {
        while (($entry = readdir($handle)) !== false) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $fullPath = $path . DIRECTORY_SEPARATOR . $entry;

            // ignore / allow check
            if (shouldIgnore($entry, $ignoreDirs, $ignoreFiles, $ALLOW_DIR_PATTERNS, $ALLOW_FILE_PATTERNS, $fullPath)) {
                continue;
            }

            $realFullPath = realpath($fullPath);
            if (!$realFullPath) {
                continue;
            }

            // relative path for links
            $relativePath = ltrim(substr($realFullPath, $rootLen), DIRECTORY_SEPARATOR);

            // build entry
            if (is_dir($realFullPath)) {
                $aliasName = getAliasOrOriginal($entry, $DIRECTORY_ALIASES);
                if (isEmptyDirectory($realFullPath)) {
                    $itemHtml = '<li class="text-muted"><i class="fas fa-folder"></i> '
                              . htmlspecialchars($aliasName) . '</li>';
                } else {
                    $itemHtml = '<li><i class="fas fa-folder"></i> '
                              . '<a href="#" data-folder="' . htmlspecialchars($relativePath, ENT_QUOTES) . '">'
                              . htmlspecialchars($aliasName) . '</a></li>';
                }
                $entriesForSort[] = [
                    'isDir' => true,
                    'name'  => $aliasName,
                    'path'  => $realFullPath,
                    'html'  => $itemHtml
                ];
            } else {
                $itemHtml = '<li><i class="fas fa-file"></i> '
                          . '<a href="' . htmlspecialchars($relativePath, ENT_QUOTES) . '" target="_blank">'
                          . htmlspecialchars($entry) . '</a></li>';
                $entriesForSort[] = [
                    'isDir' => false,
                    'name'  => $entry,
                    'path'  => $realFullPath,
                    'html'  => $itemHtml
                ];
            }
        }
        closedir($handle);
    } else {
        $output[] = '<li>Cannot open directory.</li>';
    }

    // 1) filter
    $entriesForSort = filterEntries($entriesForSort, $filter);

    // totalCount before pagination
    $totalCount = count($entriesForSort);

    // 2) sort
    $entriesForSort = sortEntries($entriesForSort, $sortMode);

    // 3) pagination
    if ($page < 1) {
        $page = 1;
    }
    $totalPages = max(1, ceil($totalCount / $itemsPerPage));
    if ($page > $totalPages) {
        $page = $totalPages;
    }

    $startIndex = ($page - 1) * $itemsPerPage;
    $pagedEntries = array_slice($entriesForSort, $startIndex, $itemsPerPage);

    // build final HTML
    foreach ($pagedEntries as $e) {
        $output[] = $e['html'];
    }

    // if at root, optionally add top-level entries from ALLOW_DIR_PATTERNS
    if ($path === $EXPLORER_PATH) {
        foreach ($ALLOW_DIR_PATTERNS as $allowedRelative) {
            $allowedFull = realpath($EXPLORER_PATH . DIRECTORY_SEPARATOR . $allowedRelative);
            if (!$allowedFull) {
                continue;
            }
            // avoid duplicates
            $alreadyListed = false;
            foreach ($output as $line) {
                if (strpos($line, 'data-folder="' . htmlspecialchars($allowedRelative, ENT_QUOTES) . '"') !== false) {
                    $alreadyListed = true;
                    break;
                }
            }
            if ($alreadyListed) {
                continue;
            }

            // alias + empty check
            $displayName = basename($allowedFull);
            $aliasName   = getAliasOrOriginal($displayName, $DIRECTORY_ALIASES);

            if (isEmptyDirectory($allowedFull)) {
                $output[] = '<li class="text-muted"><i class="fas fa-folder"></i> '
                          . htmlspecialchars($aliasName) . '</li>';
            } else {
                $output[] = '<li><i class="fas fa-folder"></i> '
                          . '<a href="#" data-folder="' . htmlspecialchars($allowedRelative, ENT_QUOTES) . '">'
                          . htmlspecialchars($aliasName) . '</a></li>';
            }
        }
    }

    $finalHtml = implode("\n", $output);

    return [
        'html'       => $finalHtml,
        'totalCount' => $totalCount,
        'page'       => $page,
        'totalPages' => $totalPages
    ];
}

/**
 * buildBreadcrumb:
 * returns an array of [ 'label' => ..., 'folder' => ... ] from root to current
 *
 * @param string $rootPath
 * @param string $currentDir
 * @return array
 */
function buildBreadcrumb($rootPath, $currentDir) {
    $crumbs    = [];
    $relative  = str_replace($rootPath, '', $currentDir);
    $parts     = array_filter(explode(DIRECTORY_SEPARATOR, $relative));

    // home link
    $crumbs[] = [ 'label' => 'Home', 'folder' => '' ];

    $pathSoFar = '';
    foreach ($parts as $part) {
        $pathSoFar = ltrim($pathSoFar . DIRECTORY_SEPARATOR . $part, DIRECTORY_SEPARATOR);
        $crumbs[] = [
            'label'  => $part,
            'folder' => $pathSoFar
        ];
    }
    return $crumbs;
}

/* --------------------------------------------------------
   3) AJAX HANDLING
   -------------------------------------------------------- */
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
    // read parameters
    $requestedFolder = $_GET['folder']         ?? '';
    $sortMode        = $_GET['sort']           ?? 'nameAsc';
    $filter          = $_GET['filter']         ?? '';
    $page            = (int)($_GET['page']     ?? 1);
    $itemsPerPage    = (int)($_GET['itemsPerPage'] ?? 20);

    // ensure path is valid (replace $ROOT_PATH with $EXPLORER_PATH)
    $safePath = buildSafePath($EXPLORER_PATH, $requestedFolder);
    if ($safePath === null) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid directory access. Path is not inside EXPLORER_PATH']);
        exit;
    }

    // build listing and breadcrumb
    $listingResult = listItems($safePath, $IGNORE_DIR_PATTERNS, $IGNORE_FILE_PATTERNS, $sortMode, $filter, $page, $itemsPerPage);
    $listHtml      = $listingResult['html'];
    $totalCount    = $listingResult['totalCount'];
    $currentPage   = $listingResult['page'];
    $totalPages    = $listingResult['totalPages'];

    // For breadcrumb, still show it from the perspective of $EXPLORER_PATH
    $breadcrumb    = buildBreadcrumb($EXPLORER_PATH, $safePath);

    // output JSON
    header('Content-Type: application/json');
    echo json_encode([
        'breadcrumb'  => $breadcrumb,
        'listHtml'    => $listHtml,
        'entryCount'  => $totalCount,
        'currentPage' => $currentPage,
        'totalPages'  => $totalPages
    ]);
    exit;
}

/* --------------------------------------------------------
   4) PAGE GENERATION
   -------------------------------------------------------- */
// generate default (root) listing for first page (replace $ROOT_PATH with $EXPLORER_PATH)
$rootListing = listItems($EXPLORER_PATH, $IGNORE_DIR_PATTERNS, $IGNORE_FILE_PATTERNS, 'nameAsc', '', 1, 20);
$fileListHtml = $rootListing['html'];
$rootCount    = $rootListing['totalCount'];

/**
 * generatePage:
 * builds the final HTML page (including sticky/fixed header).
 * also shows the number of total items in the listing-card header
 *
 * @param string $fileListHtml  the <li> items for the root listing
 * @return void   directly outputs HTML
 */
function generatePage($fileListHtml) {
    global $APP_VERSION, $rootCount;
    global $APP_TITLE, $APP_SUBTITLE, $APP_LOGO_URL,
           $COPYRIGHT_YEAR, $COPYRIGHT_OWNER, $FOOTER_TEXT,
           $NAVIGATION_TITLE, $BROWSER_TITLE;

    $logoPath    = trim($APP_LOGO_URL);
    $headerClass = (!empty($logoPath)) ? 'header-flex' : 'header-center';

    // HTML output
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$APP_TITLE} - v{$APP_VERSION}</title>

    <!-- Main CSS -->
    <link rel="stylesheet" href="default-style.css">
    <!-- Local Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Local Font Awesome CSS -->
    <link rel="stylesheet" href="assets/css/all.min.css">

    <style>
    /* Body: Add top padding to avoid overlap with fixed header */
    body {
        padding-top: 125px; /* space for fixed header */
        background-color: #2b2e38;
        color: #dce1e7;
        margin-bottom: 60px;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    }
    /* Fixed header */
    .header {
        background-color: #242731;
        color: #fff;
        margin-bottom: 20px;
        padding: 20px;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
    }
    #navCard {
        position: sticky;
        top: 125px; /* same offset as body padding */
    }
    .header-center {
        text-align: center;
    }
    .header-flex {
        display: flex;
        flex-direction: row;
        align-items: stretch;
        gap: 20px;
    }
    .header-logo img {
        max-height: 120px;
        width: auto;
        height: auto;
    }
    .header-text h1 {
        margin: 0 0 6px 0;
        font-size: 1.8rem;
    }
    .header-text .version {
        font-size: 0.9rem;
        color: #ccc;
        margin: 0 0 6px 0;
    }
    .card {
        background-color: #2f3240;
        color: #bfc4ce;
        border: 1px solid #3e414d;
    }
    .card-header {
        background-color: #2f3240;
        color: #e4e4e4;
        border-bottom: 1px solid #3e414d;
        padding: 10px 15px;
    }
    .entry-count {
        float: right;
        font-size: 0.9rem;
        color: #ccc;
    }
    .breadcrumb {
        background-color: #2f3240;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
    }
    .breadcrumb span {
        margin-right: 5px;
        cursor: pointer;
        color: #ccc;
    }
    .breadcrumb span:hover {
        color: #53fd7c;
        text-decoration: none;
    }
    footer {
        background-color: #242731;
        color: #999;
        text-align: center;
        padding: 10px;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        font-size: 0.9rem;
        z-index: 1000;
    }
    footer a {
        color: #53a7fd;
        text-decoration: none;
    }
    footer a:hover {
        text-decoration: underline;
        color: #53fd7c;
    }
    .file-list {
    }
    .scrollable-list {
        max-height: calc(100vh - 125px - 60px);
        overflow-y: auto;
    }
    .scrollable-list ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .scrollable-list li {
        margin: 2px 0; 
        padding: 2px 0;
        transition: background-color 0.2s;
        font-size: 0.9rem;
    }
    .scrollable-list li:hover {
        background-color: #393c48;
    }
    .scrollable-list a {
        color: #53a7fd;
        text-decoration: none;
        font-weight: 500;
    }
    .scrollable-list a:hover {
        color: #53fd7c;
        text-decoration: underline;
    }
    .text-muted {
        color: #777 !important;
    }
    .controls-container {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .controls-container > div {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    #filterInput {
        width: 180px;
    }
    #spinner {
        display: none;
        color: #53a7fd;
        font-size: 1.2rem;
    }
    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .pagination-controls .page-label {
        margin: 0 5px;
    }
    </style>
</head>
<body>

    <header class="header {$headerClass}">
HTML;

    // if a logo is present
    if (!empty($logoPath)) {
        echo "<div class='header-logo'><img src='{$logoPath}' alt='Logo'></div>";
    }

    // header text
    echo <<<HTML
        <div class="header-text">
            <h1>{$APP_TITLE}</h1>
            <div class="version">Version: v{$APP_VERSION}</div>
            <p>{$APP_SUBTITLE}</p>
        </div>
    </header>

    <!-- page content -->
    <div class="container-fluid content-wrapper">
        <div class="row">
            <!-- LEFT COLUMN: navigation / breadcrumb -->
            <div class="col-md-3">
                <div class="card mb-3" id="navCard">
                    <div class="card-header">
                        <i class="fas fa-sitemap"></i> {$NAVIGATION_TITLE}
                    </div>
                    <div class="card-body">
                        <div class="breadcrumb" id="breadcrumbContainer">
                            <span data-folder="">Home</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: file/folder listing -->
            <div class="col-md-9">
                <div class="card" id="listingCard">
                    <div class="card-header">
                        <i class="fas fa-folder"></i> {$BROWSER_TITLE}
                        <span class="entry-count" id="entryCountLabel">({$rootCount} entries)</span>
                    </div>
                    <div class="card-body file-list">
                        <!-- Sort & Filter controls -->
                        <div class="controls-container">
                            <div>
                                <label for="filterInput">Filter:</label>
                                <input type="text" id="filterInput" placeholder="e.g. *.log" />
                                
                                <label for="sortSelect">Sort:</label>
                                <select id="sortSelect">
                                    <option value="nameAsc">Name (A-Z)</option>
                                    <option value="nameDesc">Name (Z-A)</option>
                                    <option value="extAsc">Extension (A-Z)</option>
                                    <option value="extDesc">Extension (Z-A)</option>
                                    <option value="dateDesc">Date (newest)</option>
                                    <option value="dateAsc">Date (oldest)</option>
                                </select>
                                
                                <button id="applyBtn" class="btn btn-sm btn-primary">Apply</button>
                            </div>
                            <!-- Page navigation -->
                            <div class="pagination-controls">
                                <button id="prevPageBtn" class="btn btn-sm btn-secondary">Prev</button>
                                <span class="page-label" id="pageIndicator">Page 1 of 1</span>
                                <button id="nextPageBtn" class="btn btn-sm btn-secondary">Next</button>
                            </div>
                            <!-- Items per page -->
                            <div>
                                <label for="itemsPerPageInput">Items per page:</label>
                                <input type="number" min="1" value="20" id="itemsPerPageInput" style="width: 80px;" />
                            </div>
                            <!-- Spinner -->
                            <div id="spinner">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <!-- Scrollable container for the file list -->
                        <div class="scrollable-list">
                            <ul id="fileList">
                                {$fileListHtml}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <footer>
        &copy; {$COPYRIGHT_YEAR} {$COPYRIGHT_OWNER} {$FOOTER_TEXT}
        &bull; <a href="privacy.php" target="_blank">Privacy Policy</a>
    </footer>

    <!-- Local Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!-- Local Font Awesome JS -->
    <script src="assets/js/all.min.js"></script>

    <!-- Our main script for AJAX folder loading, pagination, sort/filter, etc. -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const STORAGE_FOLDER_KEY       = 'lastFolder';
        const STORAGE_SORT_KEY         = 'lastSort';
        const STORAGE_FILTER_KEY       = 'lastFilter';
        const STORAGE_PAGE_KEY         = 'lastPage';
        const STORAGE_ITEMS_PER_PAGE   = 'itemsPerPage';

        const fileList                 = document.getElementById('fileList');
        const breadcrumbEl             = document.getElementById('breadcrumbContainer');
        const sortSelect               = document.getElementById('sortSelect');
        const filterInput              = document.getElementById('filterInput');
        const itemsPerPageInput        = document.getElementById('itemsPerPageInput');
        const applyBtn                 = document.getElementById('applyBtn');
        const spinner                  = document.getElementById('spinner');
        const entryCountLabel          = document.getElementById('entryCountLabel');
        const pageIndicator            = document.getElementById('pageIndicator');
        const prevPageBtn              = document.getElementById('prevPageBtn');
        const nextPageBtn              = document.getElementById('nextPageBtn');

        // Load stored states
        const lastFolder   = localStorage.getItem(STORAGE_FOLDER_KEY)     || '';
        const lastSort     = localStorage.getItem(STORAGE_SORT_KEY)       || 'nameAsc';
        const lastFilter   = localStorage.getItem(STORAGE_FILTER_KEY)     || '';
        const lastPage     = parseInt(localStorage.getItem(STORAGE_PAGE_KEY) || '1', 10);
        const lastItems    = parseInt(localStorage.getItem(STORAGE_ITEMS_PER_PAGE) || '20', 10);

        // Init the controls
        sortSelect.value      = lastSort;
        filterInput.value     = lastFilter;
        itemsPerPageInput.value = lastItems.toString();

        // Keep track of current page in memory
        let currentPage = lastPage > 0 ? lastPage : 1;

        // Initial listing
        loadFolder(lastFolder, lastSort, lastFilter, currentPage, lastItems);

        // "Apply" button => apply sort/filter changes and reset to page 1
        applyBtn.addEventListener('click', function() {
            const folder       = localStorage.getItem(STORAGE_FOLDER_KEY) || '';
            currentPage        = 1; // reset to first page whenever we apply new filter/sort
            loadFolder(
                folder,
                sortSelect.value,
                filterInput.value,
                currentPage,
                parseInt(itemsPerPageInput.value, 10)
            );
        });

        // handle clicks in the fileList (folders)
        fileList.addEventListener('click', function(e) {
            if (e.target && e.target.matches('a[data-folder]')) {
                e.preventDefault();
                const folder = e.target.getAttribute('data-folder');
                currentPage  = 1; // reset to first page
                loadFolder(
                    folder,
                    sortSelect.value,
                    filterInput.value,
                    currentPage,
                    parseInt(itemsPerPageInput.value, 10)
                );
            }
        });

        // handle clicks in breadcrumb
        breadcrumbEl.addEventListener('click', function(e) {
            if (e.target && e.target.matches('span[data-folder]')) {
                const folder = e.target.getAttribute('data-folder');
                currentPage  = 1; // reset to first page
                loadFolder(
                    folder,
                    sortSelect.value,
                    filterInput.value,
                    currentPage,
                    parseInt(itemsPerPageInput.value, 10)
                );
            }
        });

        // handle Prev/Next page buttons
        prevPageBtn.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                const folder = localStorage.getItem(STORAGE_FOLDER_KEY) || '';
                loadFolder(
                    folder,
                    sortSelect.value,
                    filterInput.value,
                    currentPage,
                    parseInt(itemsPerPageInput.value, 10)
                );
            }
        });

        nextPageBtn.addEventListener('click', function() {
            const folder = localStorage.getItem(STORAGE_FOLDER_KEY) || '';
            // We'll check in loadFolder callback if there's a next page or not
            currentPage++;
            loadFolder(
                folder,
                sortSelect.value,
                filterInput.value,
                currentPage,
                parseInt(itemsPerPageInput.value, 10)
            );
        });

        // AJAX loader
        function loadFolder(subPath, sortMode, filter, page, itemsPerPage) {
            showSpinner();
            const url = '?ajax=1'
                      + '&folder='        + encodeURIComponent(subPath)
                      + '&sort='          + encodeURIComponent(sortMode)
                      + '&filter='        + encodeURIComponent(filter)
                      + '&page='          + encodeURIComponent(page)
                      + '&itemsPerPage='  + encodeURIComponent(itemsPerPage);

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('HTTP error: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    hideSpinner();
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    // update list
                    fileList.innerHTML = data.listHtml;
                    // update breadcrumb
                    updateBreadcrumb(data.breadcrumb);
                    // show new count
                    if (typeof data.entryCount !== 'undefined') {
                        entryCountLabel.textContent = `(\${data.entryCount} entries)`;
                    }
                    // update page indicator
                    if (typeof data.currentPage !== 'undefined' && typeof data.totalPages !== 'undefined') {
                        pageIndicator.textContent = 'Page ' + data.currentPage + ' of ' + data.totalPages;
                        currentPage = data.currentPage; // ensure sync
                    }
                    // enable/disable Prev button
                    prevPageBtn.disabled = (data.currentPage <= 1);
                    // enable/disable Next button
                    nextPageBtn.disabled = (data.currentPage >= data.totalPages);

                    // persist states
                    localStorage.setItem(STORAGE_FOLDER_KEY, subPath);
                    localStorage.setItem(STORAGE_SORT_KEY, sortMode);
                    localStorage.setItem(STORAGE_FILTER_KEY, filter);
                    localStorage.setItem(STORAGE_PAGE_KEY, currentPage.toString());
                    localStorage.setItem(STORAGE_ITEMS_PER_PAGE, itemsPerPage.toString());
                })
                .catch(err => {
                    hideSpinner();
                    console.error('AJAX error', err);
                    alert('An error occurred while loading the folder.');
                });
        }

        function updateBreadcrumb(crumbArray) {
            breadcrumbEl.innerHTML = '';
            crumbArray.forEach((crumb, idx) => {
                const span = document.createElement('span');
                span.textContent = crumb.label;
                span.setAttribute('data-folder', crumb.folder);
                breadcrumbEl.appendChild(span);

                if (idx < crumbArray.length - 1) {
                    const sep = document.createElement('span');
                    sep.textContent = '>';
                    sep.style.marginRight = '5px';
                    breadcrumbEl.appendChild(sep);
                }
            });
        }

        function showSpinner() {
            spinner.style.display = 'inline-block';
        }

        function hideSpinner() {
            spinner.style.display = 'none';
        }
    });
    </script>
</body>
</html>
HTML;
}

// finally, generate the page
generatePage($fileListHtml);
