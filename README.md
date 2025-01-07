# Tuxbox-Explorer

Version: 0.1.0

A lightweight PHP based project to navigate through directories and files in a secure and user-friendly way. This tool allows you to explore folders and files on a server while avoiding external dependencies and ensuring privacy.

## Features
- Breadcrumb navigation to easily jump between directories.
- Secure handling of file paths to prevent directory traversal attacks.
- Customizable ignore lists for files and directories.
- Local integration of Bootstrap and Font Awesome for styling.
- AJAX-based dynamic folder loading.

## Installation
1. Clone the repository:
2. Configure the application. Rename `config/config-sample.php` to `config/config.php` and by editing if required, to set your base directory, ignore lists and captions.
3. Place the application on a PHP-enabled server.
4. Open `index.php` in your browser to start navigating your directories.

## Usage
- Click on folders to explore their contents.
- Use the breadcrumb navigation to jump back to parent directories.
- Customize ignore lists to hide specific files or folders from the listing.

## Resources Used
This repository provides a local copy of the following resources to enable usage without external dependencies and to improve user privacy:

- Bootstrap v5.3.0
- CSS: `bootstrap.min.css`
- JS: `bootstrap.bundle.min.js`
- Source: [https://getbootstrap.com](https://getbootstrap.com)

- Font Awesome v6.4.0
- CSS: `all.min.css`
- JS: `all.min.js`
- Source: [https://fontawesome.com](https://fontawesome.com)

## License

This project is licensed under the MIT License. See [LICENSE](./LICENSE) for more details.

### Notes on the used resources:

- **Bootstrap** is provided under the MIT License. For more details, see: [Bootstrap License](https://github.com/twbs/bootstrap/blob/main/LICENSE)
- **Font Awesome** consists of various parts under different licenses:
- Icons: CC BY 4.0
- Fonts: SIL Open Font License 1.1
- Code: MIT License

Please make sure to comply with the respective license terms of the sources listed above.