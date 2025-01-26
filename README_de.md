<!-- LANGUAGE_LINKS_START -->
<span style="color: grey;">🇩🇪 German</span> | [🇬🇧 English](README_en.md)
<!-- LANGUAGE_LINKS_END -->

# Tuxbox File Explorer

[![Version](https://img.shields.io/badge/version-0.1.0-blue.svg)](https://github.com/dbt1/tuxbox-explorer)

Ein leichtgewichtiges, PHP-basiertes Projekt, um Verzeichnisse und Dateien sicher und benutzerfreundlich zu durchsuchen. Dieses Tool erlaubt es, Ordner und Dateien auf einem Server zu erkunden, ohne externe Abhängigkeiten zu benötigen, und bietet gleichzeitig ein hohes Maß an Privatsphäre.

## Features

- Einfaches Springen zwischen Verzeichnissen.
- Schutz vor Directory-Traversal-Angriffen.
- Anpassbare Ignorierlisten blenden bestimmte Dateien und Verzeichnisse aus.
- Ressourcen wie Bootstrap und Font Awesome werden lokal bereitgestellt.

## Systemanforderungen

- PHP ab Version 7.4 (empfohlen: PHP 8.0+).
- Webserver mit PHP-Unterstützung (z. B. Apache, Nginx, Lighttpd).
- PHP-Module: php-mbstring, php-json, php-xml, php-curl, php-fileinfo, php-ctype, php-iconv
- Schreib- und Leserechte für das Zielverzeichnis.

## Installation

**1. Repository klonen:**

   ```bash
   git clone https://github.com/dbt1/tuxbox-explorer && cd tuxbox-explorer
   ```
**2. Konfiguration anpassen:**

   - Benenne `config/config-sample.php` in `config/config.php` um.
   - Passe wenn erforderlich die Listen in `config.php` an, um bestimmte Dateien und Ordner ein- oder auszublenden.
     Lege den oder die Datenordner flexibel über Ignor und Allow-Listen fest, in der die gehosteten Dateien liegen, die zur Verfügung gestellt werden sollen.
   - Passe bei Bedarf die Beschriftungen und Fenstertexte in `config.php` an.
   - Lade den gesamten Inhalt aus dem geklonten Repository auf einen PHP-fähigen Server hoch.**
   - Öffne `index.php` im Browser.

## Nutzung

Klicke auf Ordner, um deren Inhalte anzuzeigen.
Nutze die Breadcrumb-Leiste, um schnell zu übergeordneten Verzeichnissen zu wechseln.

## Konfigurationshinweise

Die Datei `config.php` erlaubt die Anpassung folgender Parameter:

Absoluter Pfad, der als Wurzelverzeichnis dient.

   ```bash
   ROOT_PATH
   ```

Verzeichnisse, die ignoriert werden sollen (z. B. `.git`, `node_modules`).

   ```bash
   IGNORE_DIR_PATTERNS
   ```

Dateien, die ignoriert werden sollen (z. B. `*.log`, `*.html`).

   ```bash
   IGNORE_FILE_PATTERNS
   ```

Verzeichnisse, die trotz Ignorierliste angezeigt werden sollen.

   ```bash
   ALLOW_DIR_PATTERNS
   ```

Dateien, die trotz Ignorierliste angezeigt werden sollen.

   ```bash
   ALLOW_FILE_PATTERNS
   ```

Anzeigenamen für Verzeichnisse festlegen.

   ```bash
   DIRECTORY_ALIASES
   ```

Für die Datenschutz Informationen:

   ```bash
   DATA_CONTROLLER_NAME
   DATA_CONTROLLER_ADDRESS
   DATA_CONTROLLER_EMAIL
   DATA_CONTROLLER_EMAIL_ALIAS
   ```

## Verwendete Ressourcen

Dieses Repository stellt lokale Kopien der folgenden Ressourcen bereit, um die Nutzung ohne externe Abhängigkeiten zu ermöglichen und die Privatsphäre zu schützen:

**Bootstrap v5.3.0**

  - CSS: `bootstrap.min.css`
  - JS: `bootstrap.bundle.min.js`
  - Quelle: [https://getbootstrap.com](https://getbootstrap.com)

**Font Awesome v6.4.0**

  - CSS: `all.min.css`
  - JS: `all.min.js`
  - Quelle: [https://fontawesome.com](https://fontawesome.com)

## Lizenz

Dieses Projekt steht unter der MIT-Lizenz. Details siehe [LICENSE](./LICENSE).

### Hinweise zu den genutzten Ressourcen:

**Bootstrap**

MIT-Lizenz.

Details: [Bootstrap License](https://github.com/twbs/bootstrap/blob/main/LICENSE).

**Font Awesome**

Verschiedene Lizenzen:

  - Icons: CC BY 4.0
  - Fonts: SIL Open Font License 1.1
  - Code: MIT-Lizenz

Bitte beachte die jeweiligen Lizenzbedingungen der oben genannten Ressourcen.

