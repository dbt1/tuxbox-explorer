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
 *
 * System Requirements:
 *  - PHP >= 7.4 (idealerweise PHP 8.0+)
 *  - Webserver (Apache, Nginx, usw.) mit aktiviertem PHP
 *  - Schreib-/Leserechte auf das Zielverzeichnis (je nach Use Case)
 *  - `config.php` (oder `config-dist.php` als Vorlage) muss existieren
 */

/* --------------------------------------------------------
   0) CHECK FOR CONFIG FILE
   -------------------------------------------------------- */
$configPath = __DIR__ . '/config/config.php';
if (!file_exists($configPath)) {
    // Simple HTML response if config is missing
    header('Content-Type: text/html; charset=utf-8');
    echo "<!DOCTYPE html>
<html lang='de'>
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
$APP_VERSION = '0.1.0';

// ---------------------------
// 1) LOAD CONFIG
// ---------------------------
require_once $configPath;

// ---------------------------
// 2) HELPER FUNCTIONS
// ---------------------------

/**
 * Build a safe realpath inside the ROOT_PATH, avoiding directory traversal.
 *
 * @param string $rootPath Absolute path to the root directory
 * @param string $subPath  Requested subpath
 * @return string|null     Returns the real path if valid, otherwise null
 */
function buildSafePath($rootPath, $subPath) {
    $rootPath = rtrim(realpath($rootPath), DIRECTORY_SEPARATOR);
    $subPath  = str_replace("\0", '', $subPath);
    $subPath  = str_replace('\\', '/', $subPath);
    $subPath  = preg_replace('/\.\.+/', '', $subPath);

    $target     = $rootPath . DIRECTORY_SEPARATOR . $subPath;
    $realTarget = realpath($target);

    if ($realTarget !== false && strpos($realTarget, $rootPath) === 0) {
        return $realTarget;
    }
    return null;
}

/**
 * Check if a directory is empty (ignoring '.' and '..').
 *
 * @param string $path Absolute directory path
 * @return bool        True if empty, false otherwise
 */
function isEmptyDirectory($path) {
    if (!is_dir($path)) {
        return false;
    }
    $files = array_diff(scandir($path), ['.', '..']);
    return empty($files);
}

/**
 * Return an alias for the directory name if it exists; otherwise return the original name.
 *
 * @param string $dirName  Original directory name
 * @param array  $aliases  Associative array of aliases (dirName => alias)
 * @return string          Alias or original name
 */
function getAliasOrOriginal($dirName, array $aliases) {
    if (array_key_exists($dirName, $aliases)) {
        return $aliases[$dirName];
    }
    return $dirName;
}

/**
 * Decide if a file or directory should be ignored, based on ignore patterns and allow patterns.
 *
 * @param string $entry            Item name (e.g., "tmp", "assets", "readme.txt")
 * @param array  $ignoreDirs       Wildcard patterns for directories to ignore
 * @param array  $ignoreFiles      Wildcard patterns for files to ignore
 * @param array  $allowDirs        Wildcard patterns for directories to allow
 * @param array  $allowFiles       Wildcard patterns for files to allow
 * @param string $fullPath         Full absolute path of the item
 * @return bool                    True if ignored, false otherwise
 */
function shouldIgnore($entry, array $ignoreDirs, array $ignoreFiles, array $allowDirs, array $allowFiles, $fullPath) {
    // 1) Check allow patterns (whitelist)
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
    // 2) Check ignore patterns
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

    // If neither allowed nor ignored specifically -> not ignored
    return false;
}

/**
 * Generate a file/directory listing for a given path, applying:
 *  - wildcard-based ignore patterns (files and dirs)
 *  - wildcard-based allow patterns (files and dirs)
 *  - directory aliases
 *  - "grayed out" styling for empty folders
 *
 * @param string $path             Absolute path to list
 * @param array  $ignoreDirs       Ignore patterns for directories
 * @param array  $ignoreFiles      Ignore patterns for files
 * @return string                  HTML output (<li> elements)
 */
function listItems($path, array $ignoreDirs, array $ignoreFiles) {
    global $ROOT_PATH, $DIRECTORY_ALIASES, $ALLOW_DIR_PATTERNS, $ALLOW_FILE_PATTERNS;

    $output          = [];
    $rootRealPath    = realpath($ROOT_PATH);
    $rootLen         = strlen($rootRealPath);

    if ($handle = opendir($path)) {
        while (($entry = readdir($handle)) !== false) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $fullPath = $path . DIRECTORY_SEPARATOR . $entry;

            if (shouldIgnore(
                    $entry,
                    $ignoreDirs,
                    $ignoreFiles,
                    $ALLOW_DIR_PATTERNS,
                    $ALLOW_FILE_PATTERNS,
                    $fullPath
                )) {
                continue;
            }

            $realFullPath = realpath($fullPath);
            if (!$realFullPath) {
                continue;
            }

            $relativePath = ltrim(substr($realFullPath, $rootLen), DIRECTORY_SEPARATOR);

            if (is_dir($realFullPath)) {
                $aliasName = getAliasOrOriginal($entry, $DIRECTORY_ALIASES);
                if (isEmptyDirectory($realFullPath)) {
                    $output[] = '<li class="text-muted"><i class="fas fa-folder"></i> '
                              . htmlspecialchars($aliasName) . '</li>';
                } else {
                    $output[] = '<li><i class="fas fa-folder"></i> '
                              . '<a href="#" data-folder="' . htmlspecialchars($relativePath, ENT_QUOTES) . '">'
                              . htmlspecialchars($aliasName) . '</a></li>';
                }
            } else {
                $output[] = '<li><i class="fas fa-file"></i> '
                          . '<a href="' . htmlspecialchars($relativePath, ENT_QUOTES) . '" target="_blank">'
                          . htmlspecialchars($entry) . '</a></li>';
            }
        }
        closedir($handle);
    } else {
        $output[] = '<li>Cannot open directory.</li>';
    }

    // If we are at the root, optionally add pseudo-top-level directories from $ALLOW_DIR_PATTERNS
    if ($path === $ROOT_PATH) {
        foreach ($ALLOW_DIR_PATTERNS as $allowedRelative) {
            $allowedFull = realpath($ROOT_PATH . DIRECTORY_SEPARATOR . $allowedRelative);
            if (!$allowedFull) {
                continue;
            }
            // Avoid duplicates
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

            // Aliases + empty check
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

    return implode("\n", $output);
}

/**
 * Build a breadcrumb array from root to current directory.
 *
 * @param string $rootPath   The root directory
 * @param string $currentDir The current directory
 * @return array             Array of [ 'label' => ..., 'folder' => ... ]
 */
function buildBreadcrumb($rootPath, $currentDir) {
    $crumbs    = [];
    $relative  = str_replace($rootPath, '', $currentDir);
    $parts     = array_filter(explode(DIRECTORY_SEPARATOR, $relative));

    // Root link
    $crumbs[] = [
        'label'  => 'Home',
        'folder' => ''
    ];

    $pathSoFar = '';
    foreach ($parts as $part) {
        $pathSoFar = ltrim($pathSoFar . DIRECTORY_SEPARATOR . $part, DIRECTORY_SEPARATOR);
        $crumbs[]  = [
            'label'  => $part,
            'folder' => $pathSoFar
        ];
    }
    return $crumbs;
}

// ---------------------------
// 3) AJAX HANDLING
// ---------------------------
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
    $requestedFolder = $_GET['folder'] ?? '';
    $safePath        = buildSafePath($ROOT_PATH, $requestedFolder);

    if ($safePath === null) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid directory access. Path is not inside ROOT_PATH']);
        exit;
    }
    $listHtml   = listItems($safePath, $IGNORE_DIR_PATTERNS, $IGNORE_FILE_PATTERNS);
    $breadcrumb = buildBreadcrumb($ROOT_PATH, $safePath);

    header('Content-Type: application/json');
    echo json_encode([
        'breadcrumb' => $breadcrumb,
        'listHtml'   => $listHtml
    ]);
    exit;
}

// ---------------------------
// 4) PAGE GENERATION
// ---------------------------
$fileListHtml = listItems($ROOT_PATH, $IGNORE_DIR_PATTERNS, $IGNORE_FILE_PATTERNS);

/**
 * Generate the final HTML page, featuring:
 *  - header with optional logo
 *  - version and description
 *  - AJAX-based folder listing
 *
 * @param string $fileListHtml The generated <li> elements for the root listing
 * @return void Outputs directly to the browser
 */
function generatePage($fileListHtml) {
    global $APP_VERSION;
    global $APP_TITLE, $APP_SUBTITLE, $APP_LOGO_URL,
           $COPYRIGHT_YEAR, $COPYRIGHT_OWNER, $FOOTER_TEXT,
           $NAVIGATION_TITLE, $BROWSER_TITLE;

    // Decide layout (flex if logo is present, center if not)
    $logoPath     = trim($APP_LOGO_URL); 
    $headerClass  = (!empty($logoPath)) ? 'header-flex' : 'header-center';

    // Output HTML
    echo <<<HTML
<!DOCTYPE html>
<html lang="de">
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
    body {
        background-color: #2b2e38;
        color: #dce1e7;
        margin-bottom: 60px;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    }
    .header {
        background-color: #242731;
        color: #fff;
        margin-bottom: 20px;
        padding: 20px;
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
    .header-logo {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .header-logo img {
        max-height: 120px;
        width: auto;
        height: auto;
    }
    .header-text {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
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
    .header-text p {
        margin: 0;
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
    .file-list ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .file-list li {
        margin: 6px 0;
        padding: 4px 0;
        transition: background-color 0.2s;
    }
    .file-list li:hover {
        background-color: #393c48;
    }
    .file-list a {
        color: #53a7fd;
        text-decoration: none;
        font-weight: 500;
    }
    .file-list a:hover {
        color: #53fd7c;
        text-decoration: underline;
    }
    .text-muted {
        color: #777 !important;
    }
    footer {
        background-color: #242731;
        color: #999;
        text-align: center;
        padding: 10px;
        position: fixed;
        bottom: 0;
        width: 100%;
        font-size: 0.9rem;
    }
    footer a {
        color: #53a7fd;
        text-decoration: none;
    }
    footer a:hover {
        text-decoration: underline;
        color: #53fd7c;
    }
    </style>
</head>
<body>

    <header class="header {$headerClass}">
HTML;

    // Logo column if present
    if (!empty($logoPath)) {
        echo <<<HTML
        <div class="header-logo">
            <img src="{$logoPath}" alt="Logo">
        </div>
HTML;
    }

    // Text column
    echo <<<HTML
        <div class="header-text">
            <h1>{$APP_TITLE}</h1>
            <div class="version">Version: v{$APP_VERSION}</div>
            <p>{$APP_SUBTITLE}</p>
        </div>
    </header>

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
                    </div>
                    <div class="card-body file-list">
                        <ul id="fileList">
                            {$fileListHtml}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        &copy; {$COPYRIGHT_YEAR} {$COPYRIGHT_OWNER} {$FOOTER_TEXT}
    </footer>

    <!-- Local Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!-- Local Font Awesome JS -->
    <script src="assets/js/all.min.js"></script>

    <!-- AJAX-based navigation script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const lastFolder = localStorage.getItem('lastFolder');
        if (lastFolder) {
            loadFolder(lastFolder);
        }

        document.getElementById('fileList').addEventListener('click', function(e) {
            if (e.target && e.target.matches('a[data-folder]')) {
                e.preventDefault();
                const folder = e.target.getAttribute('data-folder');
                loadFolder(folder);
            }
        });

        document.getElementById('breadcrumbContainer').addEventListener('click', function(e) {
            if (e.target && e.target.matches('span[data-folder]')) {
                const folder = e.target.getAttribute('data-folder');
                loadFolder(folder);
            }
        });

        function loadFolder(subPath) {
            fetch('?ajax=1&folder=' + encodeURIComponent(subPath))
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    document.getElementById('fileList').innerHTML = data.listHtml;
                    updateBreadcrumb(data.breadcrumb);

                    localStorage.setItem('lastFolder', subPath);
                })
                .catch(err => {
                    console.log('AJAX error', err);
                    alert('An error occurred while loading the folder.');
                });
        }

        function updateBreadcrumb(crumbArray) {
            const container = document.getElementById('breadcrumbContainer');
            container.innerHTML = '';

            crumbArray.forEach((crumb, idx) => {
                const span = document.createElement('span');
                span.textContent = crumb.label;
                span.setAttribute('data-folder', crumb.folder);
                container.appendChild(span);

                if (idx < crumbArray.length - 1) {
                    const sep = document.createElement('span');
                    sep.textContent = '>';
                    sep.style.marginRight = '5px';
                    container.appendChild(sep);
                }
            });
        }
    });
    </script>
</body>
</html>
HTML;
}

// Actually build and output the page
generatePage($fileListHtml);
