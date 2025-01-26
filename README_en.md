<!-- LANGUAGE_LINKS_START -->
[🇩🇪 German](README_de.md) | <span style="color: grey;">🇬🇧 English</span>
<!-- LANGUAGE_LINKS_END -->
# Tuxbox File Explorer

[![Version](https://img.shields.io/badge/version-0.1.0-blue.svg)](https://github.com/dbt1/tuxbox-explorer)

A lightweight PHP-based project to browse directories and files in a secure and user-friendly manner. This tool allows you to explore folders and files on a server without the need for external dependencies, while providing a high level of privacy.
## Features

- **Breadcrumb navigation**: Easily jump between directories.
- **Secure Path Handling**: Protection against directory traversal attacks.
- **Customizable Ignore Lists**: Hides specific files and directories.
- **Local Resources**: Bootstrap and Font Awesome are provided locally.
- **Dynamic reloading**: Folder contents are loaded via AJAX.
## System requirements

- PHP from version 7.4 (recommended: PHP 8.0+).
- Web server with PHP support (e.g. Apache, Nginx, Lighttpd).
- PHP modules: php-mbstring, php-json, php-xml, php-curl, php-fileinfo, php-ctype, php-iconv
- Write and read permissions for the target directory.
## installation

1. **Clone repository:**
   ```bash
   git clone https://github.com/dbt1/tuxbox-explorer && cd tuxbox-explorer
   ```
2. **Adjust configuration:**
   - Rename `config/config-sample.php` to `config/config.php`.
   - If necessary, adjust the lists in `config.php` to show or hide specific files and folders.
     Use ignore and allow lists to flexibly specify the data folder(s) in which the hosted files that should be made available are located.
   - If necessary, adjust the labels and window texts in `config.php`.
3. **Upload all content from the cloned repository to a PHP-enabled server.**
4. **Start:**
   Open `index.php` in the browser.
## use

- **Browse folder:**
  Click folders to view their contents.
- **Breadcrumb navigation:**
  Use the breadcrumb bar to quickly move to higher-level directories.
## Configuration notes

The `config.php` file allows the following parameters to be adjusted:

- **ROOT\_PATH**: Absolute path that serves as the root directory.
- **IGNORE\_DIR\_PATTERNS**: Directories to be ignored (e.g. `.git`, `node_modules`).
- **IGNORE\_FILE\_PATTERNS**: Files to be ignored (e.g. `*.log`, `*.html`).
- **ALLOW\_DIR\_PATTERNS**: Directories that should be displayed despite the ignore list.
- **ALLOW\_FILE\_PATTERNS**: Files that should be displayed despite the ignore list.
- **DIRECTORY\_ALIASES**: Set display names for directories.

For data protection information:

- **DATA\_CONTROLLER\_NAME**
- **DATA\_CONTROLLER\_ADDRESS**
- **DATA\_CONTROLLER\_EMAIL**
- **DATA\_CONTROLLER\_EMAIL\_ALIAS**
## Resources used

This repository provides local copies of the following resources to enable use without external dependencies and to protect privacy:

- **Bootstrap v5.3.0**

  - CSS: `bootstrap.min.css`
  - JS: `bootstrap.bundle.min.js`
  - Source: [https://getbootstrap.com](https://getbootstrap.com)

- **Font Awesome v6.4.0**

  - CSS: `all.min.css`
  - JS: `all.min.js`
  - Source: [https://fontawesome.com](https://fontawesome.com)
## License

This project is under the MIT license. For details see [LICENSE](./LICENSE).
### Notes on the resources used:

- **Bootstrap**: MIT license. Details: [Bootstrap License](https://github.com/twbs/bootstrap/blob/main/LICENSE).
- **Font Awesome**: Various licenses:
  - Icons: CC BY 4.0
  - Fonts: SIL Open Font License 1.1
  - Code: MIT license

Please note the respective license terms of the resources mentioned above.
