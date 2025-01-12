# Tuxbox File Explorer

[![Version](https://img.shields.io/badge/version-0.1.0-blue.svg)](https://github.com/dbt1/tuxbox-explorer)

Ein leichtgewichtiges, PHP-basiertes Projekt, um Verzeichnisse und Dateien sicher und benutzerfreundlich zu durchsuchen. Dieses Tool erlaubt es, Ordner und Dateien auf einem Server zu erkunden, ohne externe Abhängigkeiten zu benötigen, und bietet gleichzeitig ein hohes Maß an Privatsphäre.

## Features

- **Breadcrumb-Navigation**: Einfaches Springen zwischen Verzeichnissen.
- **Sichere Pfadbehandlung**: Schutz vor Directory-Traversal-Angriffen.
- **Anpassbare Ignorierlisten**: Blendet bestimmte Dateien und Verzeichnisse aus.
- **Lokale Ressourcen**: Bootstrap und Font Awesome werden lokal bereitgestellt.
- **Dynamisches Nachladen**: Ordnerinhalte werden per AJAX geladen.

## Installation

1. **Repository klonen:**
   ```bash
   git clone https://github.com/dbt1/tuxbox-explorer && cd tuxbox-explorer
   ```
2. **Konfiguration anpassen:**
   - Benenne `config/config-sample.php` in `config/config.php` um.
   - Passe wenn erforderlich die Listen in `config.php` an, um bestimmte Dateien und Ordner ein- oder auszublenden.
   - Passe bei Bedarf die Beschriftungen und Fenstertexte in `config.php` an.
2. **Lade den gesamten Inhalt aus dem geklonten Repository auf einen PHP-fähigen Server hoch.**
3. **Starten:**
   Öffne `index.php` im Browser.

## Nutzung

- **Ordner durchsuchen:**
  Klicke auf Ordner, um deren Inhalte anzuzeigen.
- **Breadcrumb-Navigation:**
  Nutze die Breadcrumb-Leiste, um schnell zu übergeordneten Verzeichnissen zu wechseln.

## Systemanforderungen

- PHP ab Version 7.4 (empfohlen: PHP 8.0+).
- Webserver mit PHP-Unterstützung (z. B. Apache, Nginx, Lighttpd).
- Schreib- und Leserechte für das Zielverzeichnis.

## Konfigurationshinweise

Die Datei `config.php` erlaubt die Anpassung folgender Parameter:

- **ROOT\_PATH**: Absoluter Pfad, der als Wurzelverzeichnis dient.
- **IGNORE\_DIR\_PATTERNS**: Verzeichnisse, die ignoriert werden sollen (z. B. `.git`, `node_modules`).
- **IGNORE\_FILE\_PATTERNS**: Dateien, die ignoriert werden sollen (z. B. `*.log`, `*.html`).
- **ALLOW\_DIR\_PATTERNS**: Verzeichnisse, die trotz Ignorierliste angezeigt werden sollen.
- **ALLOW\_FILE\_PATTERNS**: Dateien, die trotz Ignorierliste angezeigt werden sollen.
- **DIRECTORY\_ALIASES**: Anzeigenamen für Verzeichnisse festlegen.
- 
Für die Datenschutz Informationen:

- **DATA_CONTROLLER_NAME**
- **DATA_CONTROLLER_ADDRESS**
- **DATA_CONTROLLER_EMAIL**
- **DATA_CONTROLLER_EMAIL_ALIAS**

## Verwendete Ressourcen

Dieses Repository stellt lokale Kopien der folgenden Ressourcen bereit, um die Nutzung ohne externe Abhängigkeiten zu ermöglichen und die Privatsphäre zu schützen:

- **Bootstrap v5.3.0**

  - CSS: `bootstrap.min.css`
  - JS: `bootstrap.bundle.min.js`
  - Quelle: [https://getbootstrap.com](https://getbootstrap.com)

- **Font Awesome v6.4.0**

  - CSS: `all.min.css`
  - JS: `all.min.js`
  - Quelle: [https://fontawesome.com](https://fontawesome.com)

## Lizenz

Dieses Projekt steht unter der MIT-Lizenz. Details siehe [LICENSE](./LICENSE).

### Hinweise zu den genutzten Ressourcen:

- **Bootstrap**: MIT-Lizenz. Details: [Bootstrap License](https://github.com/twbs/bootstrap/blob/main/LICENSE).
- **Font Awesome**: Verschiedene Lizenzen:
  - Icons: CC BY 4.0
  - Fonts: SIL Open Font License 1.1
  - Code: MIT-Lizenz

Bitte beachte die jeweiligen Lizenzbedingungen der oben genannten Ressourcen.

