<!-- LANGUAGE_LINKS_START -->
[🇩🇪 German](README_de.md) | [🇬🇧 English](README_en.md) | [🇪🇸 Spanish](README_es.md) | [🇫🇷 French](README_fr.md) | <span style="color: grey;">🇮🇹 Italian</span>
<!-- LANGUAGE_LINKS_END -->


# Esplora file Tuxbox

[![Version](https://img.shields.io/badge/version-0.1.0-blue.svg)](https://github.com/dbt1/tuxbox-explorer)

Un progetto leggero basato su PHP per sfogliare directory e file in modo sicuro e facile da usare. Questo strumento ti consente di esplorare cartelle e file su un server senza la necessità di dipendenze esterne, fornendo allo stesso tempo un elevato livello di privacy.
## Caratteristiche

- **Navigazione breadcrumb**: passa facilmente da una directory all'altra.
- **Gestione sicura del percorso**: protezione contro gli attacchi trasversali alle directory.
- **Elenchi da ignorare personalizzabili**: nasconde file e directory specifici.
- **Risorse locali**: Bootstrap e Font Awesome sono forniti localmente.
- **Ricaricamento dinamico**: i contenuti della cartella vengono caricati tramite AJAX.
## Requisiti di sistema

- PHP dalla versione 7.4 (consigliato: PHP 8.0+).
- Server Web con supporto PHP (ad esempio Apache, Nginx, Lighttpd).
- Moduli PHP: php-mbstring, php-json, php-xml, php-curl, php-fileinfo, php-ctype, php-iconv
- Scrittura e lettura dei permessi per la directory di destinazione.
## installazione

1. **Clona archivio:**
   ```bash
   git clone https://github.com/dbt1/tuxbox-explorer && cd tuxbox-explorer
   ```
2. **Regola la configurazione:**
   - Rinomina `config/config-sample.php` in `config/config.php`.
   - Se necessario, modifica gli elenchi in `config.php` per mostrare o nascondere file e cartelle specifici.
     Utilizzare gli elenchi Ignora e Consenti per specificare in modo flessibile le cartelle di dati in cui si trovano i file ospitati che dovrebbero essere resi disponibili.
   - Se necessario, modifica le etichette e i testi delle finestre in `config.php`.
3. **Carica tutto il contenuto dal repository clonato su un server abilitato per PHP.**
4. **Inizio:**
   Apri `index.php` nel browser.
## utilizzo

- **Sfoglia cartella:**
  Fare clic sulle cartelle per visualizzarne il contenuto.
- **Navigazione breadcrumb:**
  Utilizza la barra breadcrumb per spostarti rapidamente nelle directory di livello superiore.
## Note di configurazione

Il file `config.php` consente di regolare i seguenti parametri:

- **ROOT\_PATH**: percorso assoluto che funge da directory principale.
- **IGNORE\_DIR\_PATTERNS**: Directory da ignorare (es. `.git`, `node_modules`).
- **IGNORE\_FILE\_PATTERNS**: file da ignorare (ad esempio `*.log`, `*.html`).
- **ALLOW\_DIR\_PATTERNS**: directory che dovrebbero essere visualizzate nonostante l'elenco da ignorare.
- **ALLOW\_FILE\_PATTERNS**: file che dovrebbero essere visualizzati nonostante l'elenco da ignorare.
- **DIRECTORY\_ALIASES**: imposta i nomi visualizzati per le directory.

Per informazioni sulla protezione dei dati:

- **DATI\_CONTROLLORE\_NOME**
- **DATI\_CONTROLLORE\_INDIRIZZO**
- **DATI\_CONTROLLORE\_EMAIL**
- **DATI\_CONTROLLER\_EMAIL\_ALIAS**
## Risorse utilizzate

Questo repository fornisce copie locali delle seguenti risorse per consentirne l'uso senza dipendenze esterne e per proteggere la privacy:

- **Bootstrap v5.3.0**

  - CSS: `bootstrap.min.css`
  - JS: `bootstrap.bundle.min.js`
  - Fonte: [https://getbootstrap.com](https://getbootstrap.com)

- **Font Awesome v6.4.0**

  - CSS: `all.min.css`
  - JS: `all.min.js`
  - Fonte: [https://fontawesome.com](https://fontawesome.com)
## Licenza

Questo progetto è sotto la licenza MIT. Per i dettagli vedere [LICENZA](./LICENZA).
### Note sulle risorse utilizzate:

- **Bootstrap**: licenza MIT. Dettagli: [Licenza Bootstrap](https://github.com/twbs/bootstrap/blob/main/LICENSE).
- **Font Awesome**: Varie licenze:
  - Icone: CC BY 4.0
  - Caratteri: licenza SIL Open Font 1.1
  - Codice: licenza MIT

Si prega di notare i rispettivi termini di licenza delle risorse sopra menzionate.
