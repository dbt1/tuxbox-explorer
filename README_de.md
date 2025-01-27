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

### Repository klonen

   ```bash
   git clone https://github.com/dbt1/tuxbox-explorer && cd tuxbox-explorer
   ```
### Konfiguration anpassen

   - Benenne `config/config-sample.php` in `config/config.php` um.
   - Passe wenn erforderlich die Listen in `config.php` an, um bestimmte Dateien und Ordner ein- oder auszublenden.
   - Passe bei Bedarf die Beschriftungen und Fenstertexte in `config.php` an.
   - Lade den gesamten Inhalt aus dem geklonten Repository auf einen PHP-fähigen Server hoch.
   - Öffne `index.php` im Browser.

## Konfigurationsoptionen

Der PHP Directory Explorer bietet eine einfache Möglichkeit, Dateien und Verzeichnisse auf deinem Server zu durchsuchen. Mit der Datei `config.php` kannst du das Verhalten und das Design des Explorers anpassen. Benenne dazu die Datei `config.example.php` in `config.php` um und bearbeite folgende Optionen:

### Allgemeine Einstellungen

#### Wurzel- und Dateiverzeichnis
- **`ROOT_PATH`**: Der Hauptordner, ab dem Dateien und Verzeichnisse angezeigt werden. Standardmäßig ist dies das übergeordnete Verzeichnis des Ordners `config`.
- **`FILES_DIRECTORY`** *(Optional)*: Ein bestimmtes Verzeichnis, dessen Dateien angezeigt werden sollen. Wenn leer oder ungültig, wird `ROOT_PATH` verwendet.

#### Datenschutzinformationen
- **`DATA_CONTROLLER_NAME`**: Dein Name oder der Name deines Unternehmens.
- **`DATA_CONTROLLER_ADDRESS`**: Deine Adresse für rechtliche oder Kontaktzwecke.
- **`DATA_CONTROLLER_EMAIL`**: Die E-Mail-Adresse für Kontaktanfragen.
- **`DATA_CONTROLLER_EMAIL_ALIAS`**: Ein Alias wie "Kontakt" anstelle der E-Mail-Adresse.

### Filter- und Anzeigeoptionen

#### Verzeichnisse und Dateien ausschließen
- **`IGNORE_DIR_PATTERNS`**: Liste von Verzeichnissen, die ignoriert werden sollen (z. B. `vendor`, `.git*`).
- **`IGNORE_FILE_PATTERNS`**: Dateien, die nicht angezeigt werden sollen (z. B. `*.log`, `index.php`).

#### Ausnahmen festlegen
- **`ALLOW_DIR_PATTERNS`**: Verzeichnisse, die angezeigt werden sollen, auch wenn sie in der Ausschlussliste stehen.
- **`ALLOW_FILE_PATTERNS`**: Dateien, die angezeigt werden sollen, auch wenn sie in der Ausschlussliste stehen.

#### Verzeichnis-Aliase
- **`DIRECTORY_ALIASES`**: Vergib benutzerfreundliche Namen für bestimmte Verzeichnisse, z. B. wird `tmp` als "Temporär" angezeigt.

### Anpassung des Erscheinungsbilds

#### Titel und Logo
- **`APP_TITLE`**: Der Haupttitel, der oben auf der Seite angezeigt wird.
- **`APP_SUBTITLE`** *(Optional)*: Ein Untertitel oder eine kurze Beschreibung.
- **`APP_LOGO_URL`** *(Optional)*: URL oder Pfad zu einem Logo (PNG, JPEG, GIF, SVG). Wenn kein Logo angegeben wird, bleibt das Layout zentriert.

#### Footer- und Navigationstexte
- **`COPYRIGHT_YEAR`**: Zeigt automatisch das aktuelle Jahr im Footer an.
- **`COPYRIGHT_OWNER`**: Dein Name oder Unternehmen, der/das im Footer angezeigt wird.
- **`FOOTER_TEXT`**: Freitext für zusätzliche Hinweise oder Haftungsausschlüsse.
- **`NAVIGATION_TITLE`**: Überschrift über der Navigationsleiste.
- **`BROWSER_TITLE`**: Titel über der Datei- und Ordnerliste.

### Beispiele für den Einsatz

Mit diesen Einstellungen kannst du den Explorer individuell gestalten. Zum Beispiel:
- Dateien aus einem bestimmten Verzeichnis anzeigen.
- Das Design an deine Marke anpassen.
- Verzeichnisse oder Dateien gezielt ein- oder ausblenden, um die Übersichtlichkeit zu erhöhen.

Weitere Details findest du in den Kommentaren in der Datei `config.php`. Viel Spaß beim Anpassen!

## Nutzung

- Klicke auf Ordner, um deren Inhalte anzuzeigen.
- Nutze die Breadcrumb-Leiste, um schnell zu übergeordneten Verzeichnissen zu wechseln.

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

