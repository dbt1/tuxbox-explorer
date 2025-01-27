<!-- LANGUAGE_LINKS_START -->
[🇩🇪 German](README_de.md) | <span style="color: grey;">🇬🇧 English</span>
<!-- LANGUAGE_LINKS_END -->

# Tuxbox File Explorer

[![Version](https://img.shields.io/badge/version-0.1.17-blue.svg)](https://github.com/dbt1/tuxbox-explorer)

A lightweight PHP-based project to securely and user-friendly browse directories and files. This tool allows you to explore folders and files on a server without external dependencies while ensuring a high level of privacy.

## Features

- Easy navigation between directories.
- Protection against directory traversal attacks.
- Customizable ignore lists to exclude specific files and directories.
- Local hosting of resources like Bootstrap and Font Awesome.

## System Requirements

- PHP version 7.4 or higher (recommended: PHP 8.0+).
- Web server with PHP support (e.g., Apache, Nginx, Lighttpd).
- PHP modules: php-mbstring, php-json, php-xml, php-curl, php-fileinfo, php-ctype, php-iconv
- Read and write permissions for the target directory.

## Installation

### Clone the Repository

   ```bash
   git clone https://github.com/dbt1/tuxbox-explorer && cd tuxbox-explorer
   ```
### Adjust Configuration

   - Rename `config/config-sample.php` to `config/config.php`.
   - Customize the lists in `config.php` to include or exclude specific files and folders.
   - Adjust labels and window texts in `config.php` if needed.
   - Upload the entire content from the cloned repository to a PHP-enabled server.
   - Open `index.php` in your browser.

## Configuration Options

The PHP Directory Explorer provides a simple way to browse files and directories on your server. Using the `config.php` file, you can adjust the explorer's behavior and appearance. To get started, rename `config.example.php` to `config.php` and configure the following options:

### General Settings

#### Root and Files Directory
- **`ROOT_PATH`**: The main folder from which files and directories are displayed. By default, this is the parent directory of the `config` folder.
- **`FILES_DIRECTORY`** *(Optional)*: A specific directory whose files should be displayed. If empty or invalid, `ROOT_PATH` will be used.

#### Privacy Information
- **`DATA_CONTROLLER_NAME`**: Your name or your company's name.
- **`DATA_CONTROLLER_ADDRESS`**: Your address for legal or contact purposes.
- **`DATA_CONTROLLER_EMAIL`**: The email address for contact inquiries.
- **`DATA_CONTROLLER_EMAIL_ALIAS`**: An alias like "Contact Us" instead of displaying the email address.

### Filtering and Display Options

#### Exclude Directories and Files
- **`IGNORE_DIR_PATTERNS`**: List of directories to ignore (e.g., `vendor`, `.git*`).
- **`IGNORE_FILE_PATTERNS`**: Files to exclude (e.g., `*.log`, `index.php`).

#### Define Exceptions
- **`ALLOW_DIR_PATTERNS`**: Directories to display even if listed in the ignore patterns.
- **`ALLOW_FILE_PATTERNS`**: Files to display even if listed in the ignore patterns.

#### Directory Aliases
- **`DIRECTORY_ALIASES`**: Assign user-friendly names to certain directories, e.g., map `tmp` to "Temporary".

### Appearance Customization

#### Titles and Logo
- **`APP_TITLE`**: The main title displayed at the top of the page.
- **`APP_SUBTITLE`** *(Optional)*: A subtitle or short description.
- **`APP_LOGO_URL`** *(Optional)*: URL or path to a logo (PNG, JPEG, GIF, SVG). If no logo is provided, the layout is centered.

#### Footer and Navigation Texts
- **`COPYRIGHT_YEAR`**: Automatically displays the current year in the footer.
- **`COPYRIGHT_OWNER`**: Your name or company displayed in the footer.
- **`FOOTER_TEXT`**: Customizable text for additional notes or disclaimers.
- **`NAVIGATION_TITLE`**: Title displayed above the navigation bar.
- **`BROWSER_TITLE`**: Title displayed above the file and folder list.

### Usage Examples

With these settings, you can tailor the explorer for various scenarios, such as:
- Displaying files from a specific directory.
- Customizing the design to match your branding.
- Excluding or including directories and files to simplify the interface.

For more details, refer to the comments in the `config.php` file. Enjoy customizing!

## Usage

- Click on folders to view their contents.
- Use the breadcrumb bar to quickly navigate to parent directories.

## Included Resources

This repository provides local copies of the following resources to ensure privacy and avoid external dependencies:

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

### Notes on Included Resources:

**Bootstrap**

MIT License.

Details: [Bootstrap License](https://github.com/twbs/bootstrap/blob/main/LICENSE).

**Font Awesome**

Various licenses:

  - Icons: CC BY 4.0
  - Fonts: SIL Open Font License 1.1
  - Code: MIT License

Please ensure to comply with the respective licenses of the mentioned resources.


