<?php
/**
 * config.php
 *
 * Example configuration for our PHP directory explorer.
 * Rename this file to "config.php" and adjust values as needed.
 *
 * System Requirements:
 *  - PHP >= 7.4 (better 8.0+)
 *  - Webserver with PHP-support
 */

/**
 * ROOT_PATH:
 * The absolute path you want to serve as the "root" for the explorer.
 * By default, we pick the parent directory of "/config".
 * Adjust as needed.
 */
$ROOT_PATH = realpath(__DIR__ . '/../');

// Data controller information for the privacy policy site
$DATA_CONTROLLER_NAME    = "Your Company / Your Name";
$DATA_CONTROLLER_ADDRESS = "Your Address";
$DATA_CONTROLLER_EMAIL   = "youremail@example.com";
// Email display alias (z.B. "Contact Us" statt der E-Mail-Adresse)
$DATA_CONTROLLER_EMAIL_ALIAS = "Contact Us";

/**
 * IGNORE_DIR_PATTERNS:
 * Wildcard patterns for directories to be ignored.
 * e.g. ["vendor", "storage", "cache*", ".git", "*"] etc.
 * Patterns are matched case-insensitively.
 */
$IGNORE_DIR_PATTERNS = [
    'assets',
    'config',
    'node_modules',
    '.git',
    // More patterns or "*"
];

/**
 * IGNORE_FILE_PATTERNS:
 * Wildcard patterns for files to be ignored.
 * e.g. ["*.log", "*.php", "Thumbs.db"]
 */
$IGNORE_FILE_PATTERNS = [
    '*.css',
    'favicon.ico',
    '.gitignore',
    '*.html',
    'index.php',
    '*.js',
    'LICENSE',
    '*.md',
    'privacy.php',
    // ...
];

/**
 * ALLOW_DIR_PATTERNS:
 * If a directory matches any of these patterns, it won't be ignored
 * even if it appears in the ignore list.
 * e.g. ["specialFolder*", "foo"]
 */
$ALLOW_DIR_PATTERNS = [
    // "tmp",
    // "deploy",
];

/**
 * ALLOW_FILE_PATTERNS:
 * If a file matches any of these patterns, it won't be ignored
 * even if it appears in the ignore list.
 * e.g. ["*.ipk", "*.docx"]
 */
$ALLOW_FILE_PATTERNS = [
    // "*.ipk",
    // "*.docx"
];

/**
 * DIRECTORY_ALIASES:
 * Map real directory names to an alias.
 * e.g. ["tmp" => "Temporary", "var" => "System Logs"]
 */
$DIRECTORY_ALIASES = [
    //'tmp' => 'Temporary',
];

/**
 * APP_TITLE:
 * The main heading for the explorer.
 */
$APP_TITLE = "File Explorer";

/**
 * APP_SUBTITLE:
 * Subtitle or short description displayed below the title.
 */
$APP_SUBTITLE = "";//"Browse your files easily.";

/**
 * APP_LOGO_URL:
 * Optional URL or path to your logo image (PNG, JPEG, GIF, SVG).
 * If empty, layout is centered. If set, the logo appears to the left.
 * e.g. "assets/images/logo.png" or "https://example.com/logo.svg"
 */
$APP_LOGO_URL = "assets/images/logo.png";

/**
 * COPYRIGHT_YEAR, COPYRIGHT_OWNER, FOOTER_TEXT:
 * For the page footer. Typically includes your name/company, year, disclaimers etc.
 */
$COPYRIGHT_YEAR  = date("Y");
$COPYRIGHT_OWNER = "Thilo Graf";
$FOOTER_TEXT     = " &bullet; File Explorer &bullet; All rights reserved.";

/**
 * NAVIGATION_TITLE:
 * Title displayed above the navigation/breadcrumb panel.
 */
$NAVIGATION_TITLE = "Main Navigation";

/**
 * BROWSER_TITLE:
 * Title displayed above the file/folder listing panel.
 */
$BROWSER_TITLE = "Select Files and Folders";
