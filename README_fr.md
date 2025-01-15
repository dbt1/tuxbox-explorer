<!-- LANGUAGE_LINKS_START -->
[🇩🇪 German](README_de.md) | [🇬🇧 English](README_en.md) | [🇪🇸 Spanish](README_es.md) | <span style="color: grey;">🇫🇷 French</span> | [🇮🇹 Italian](README_it.md)
<!-- LANGUAGE_LINKS_END -->


# Explorateur de fichiers Tuxbox

[![Version](https://img.shields.io/badge/version-0.1.0-blue.svg)](https://github.com/dbt1/tuxbox-explorer)

Un projet léger basé sur PHP pour parcourir les répertoires et les fichiers de manière sécurisée et conviviale. Cet outil vous permet d'explorer des dossiers et des fichiers sur un serveur sans avoir besoin de dépendances externes, tout en offrant un haut niveau de confidentialité.
## Caractéristiques

- **Navigation dans le fil d'Ariane** : passez facilement d'un répertoire à l'autre.
- **Secure Path Handling** : Protection contre les attaques par traversée de répertoires.
- **Listes d'ignorer personnalisables** : masque des fichiers et des répertoires spécifiques.
- **Ressources locales** : Bootstrap et Font Awesome sont fournis localement.
- **Rechargement dynamique** : le contenu du dossier est chargé via AJAX.
## Configuration système requise

- PHP à partir de la version 7.4 (recommandé : PHP 8.0+).
- Serveurs Web avec support PHP (par exemple Apache, Nginx, Lighttpd).
- Modules PHP : php-mbstring, php-json, php-xml, php-curl, php-fileinfo, php-ctype, php-iconv
- Autorisations d'écriture et de lecture pour le répertoire cible.
## installation

1. **Cloner le dépôt :**
   ```bash
   git clone https://github.com/dbt1/tuxbox-explorer && cd tuxbox-explorer
   ```
2. **Ajuster la configuration :**
   - Renommez `config/config-sample.php` en `config/config.php`.
   - Si nécessaire, ajustez les listes dans `config.php` pour afficher ou masquer des fichiers et dossiers spécifiques.
     Utilisez les listes Ignorer et Autoriser pour spécifier de manière flexible le(s) dossier(s) de données dans lesquels se trouvent les fichiers hébergés qui doivent être rendus disponibles.
   - Si nécessaire, ajustez les étiquettes et les textes des fenêtres dans `config.php`.
3. **Téléchargez tout le contenu du référentiel cloné vers un serveur compatible PHP.**
4. **Démarrer :**
   Ouvrez `index.php` dans le navigateur.
## utiliser

- **Parcourir le dossier :**
  Cliquez sur les dossiers pour afficher leur contenu.
- **Navigation dans le fil d'Ariane :**
  Utilisez le fil d'Ariane pour accéder rapidement aux répertoires de niveau supérieur.
## Notes de configuration

Le fichier `config.php` permet d'ajuster les paramètres suivants :

- **ROOT\_PATH** : Chemin absolu qui sert de répertoire racine.
- **IGNORE\_DIR\_PATTERNS** : Répertoires à ignorer (par exemple `.git`, `node_modules`).
- **IGNORE\_FILE\_PATTERNS** : Fichiers à ignorer (par exemple `*.log`, `*.html`).
- **ALLOW\_DIR\_PATTERNS** : Répertoires qui doivent être affichés malgré la liste des ignorés.
- **ALLOW\_FILE\_PATTERNS** : Fichiers qui doivent être affichés malgré la liste des ignorés.
- **DIRECTORY\_ALIASES** : définissez les noms d'affichage pour les répertoires.

Pour les informations sur la protection des données :

- **DONNÉES\_CONTROLLER\_NAME**
- **DONNÉES\_CONTROLLER\_ADDRESS**
- **DONNÉES\_CONTROLLER\_EMAIL**
- **DONNÉES\_CONTROLLER\_EMAIL\_ALIAS**
## Ressources utilisées

Ce référentiel fournit des copies locales des ressources suivantes pour permettre une utilisation sans dépendances externes et pour protéger la confidentialité :

- **Bootstrap v5.3.0**

  -CSS : `bootstrap.min.css`
  -JS : `bootstrap.bundle.min.js`
  - Source : [https://getbootstrap.com](https://getbootstrap.com)

- **Police géniale v6.4.0**

  -CSS : `all.min.css`
  -JS : `all.min.js`
  - Source : [https://fontawesome.com](https://fontawesome.com)
## Licence

Ce projet est sous licence MIT. Pour plus de détails, voir [LICENCE](./LICENSE).
### Notes sur les ressources utilisées :

- **Bootstrap** : licence MIT. Détails : [Licence Bootstrap](https://github.com/twbs/bootstrap/blob/main/LICENSE).
- **Font Awesome** : Diverses licences :
  - Icônes : CC BY 4.0
  - Polices : licence de police ouverte SIL 1.1
  - Code : licence MIT

Veuillez noter les conditions de licence respectives des ressources mentionnées ci-dessus.
