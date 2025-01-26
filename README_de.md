<!-- LANGUAGE_LINKS_START -->
<span style="color: grey;">🇩🇪 German</span> | [🇬🇧 English](README_en.md)
<!-- LANGUAGE_LINKS_END -->

# Tuxbox File Explorer

[![Version](https://img.shields.io/badge/version-0.1.0-blue.svg)](https://github.com/dbt1/tuxbox-explorer)

Please provide in the editor window

A lightweight, PHP-based project to securely and user-friendly browse directories and files. This tool allows exploring folders and files on a server without requiring external dependencies while offering a high level of privacy.

## Features

- Easy navigation between directories.
- Protection against directory traversal attacks.
- Customizable ignore lists to hide specific files and directories.
- Resources like Bootstrap and Font Awesome are provided locally.

## System Requirements

- PHP version 7.4 or higher (recommended: PHP 8.0+).
- Web server with PHP support (e.g., Apache, Nginx, Lighttpd).
- PHP modules: php-mbstring, php-json, php-xml, php-curl, php-fileinfo, php-ctype, php-iconv
- Write and read permissions for the target directory.

## Installation

**1. Clone the repository:**

   ```bash
   git clone https://github.com/dbt1/tuxbox-explorer && cd tuxbox-explorer
   ```
**2. Adjust configuration:**

   - Rename `config/config-sample.php` to `config/config.php`.
   - Modify the lists in `config.php` if necessary to include or exclude specific files and folders.
     Define the data folder(s) flexibly through ignore and allow lists to specify which hosted files should be made available.
   - Adjust the labels and window texts in `config.php` if required.
   - Upload the entire content of the cloned repository to a PHP-enabled server.
   - Open `index.php` in the browser.

## Usage

Click on folders to view their contents.
Use the breadcrumb navigation bar to quickly switch to parent directories.

## Configuration Notes

The `config.php` file allows customization of the following parameters:

Absolute path to serve as the root directory.

   ```bash
   ROOT_PATH
   ```

Directories to ignore (e.g., `.git`, `node_modules`).

   ```bash
   IGNORE_DIR_PATTERNS
   ```

Files to ignore (e.g., `*.log`, `*.html`).

   ```bash
   IGNORE_FILE_PATTERNS
   ```

Directories to display despite the ignore list.

   ```bash
   ALLOW_DIR_PATTERNS
   ```

Files to display despite the ignore list.

   ```bash
   ALLOW_FILE_PATTERNS
   ```

Define display names for directories.

   ```bash
   DIRECTORY_ALIASES
   ```

For data protection information:

   ```bash
   DATA_CONTROLLER_NAME
   DATA_CONTROLLER_ADDRESS
   DATA_CONTROLLER_EMAIL
   DATA_CONTROLLER_EMAIL_ALIAS
   ```

## Used Resources

This repository provides local copies of the following resources to enable usage without external dependencies and ensure privacy:

**Bootstrap v5.3.0**

  - CSS: `bootstrap.min.css`
  - JS: `bootstrap.bundle.min.js`
  - Source: [https://getbootstrap.com](https://getbootstrap.com)

**Font Awesome v6.4.0**

  - CSS: `all.min.css`
  - JS: `all.min.js`
  - Source: [https://fontawesome.com](https://fontawesome.com)

## License

This project is licensed under the MIT License. See [LICENSE](./LICENSE) for details.

### Notes on Used Resources:

**Bootstrap**

MIT License.

Details: [Bootstrap License](https://github.com/twbs/bootstrap/blob/main/LICENSE).

**Font Awesome**

Various licenses:

  - Icons: CC BY 4.0
  - Fonts: SIL Open Font License 1.1
  - Code: MIT License

Please refer to the respective license terms of the resources mentioned above.

