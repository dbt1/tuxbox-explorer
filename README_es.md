<!-- LANGUAGE_LINKS_START -->
[🇩🇪 German](README_de.md) | [🇬🇧 English](README_en.md) | <span style="color: grey;">🇪🇸 Spanish</span> | [🇫🇷 French](README_fr.md) | [🇮🇹 Italian](README_it.md)
<!-- LANGUAGE_LINKS_END -->
# Explorador de archivos Tuxbox

[![Version](https://img.shields.io/badge/version-0.1.0-blue.svg)](https://github.com/dbt1/tuxbox-explorer)

Un proyecto ligero basado en PHP para explorar directorios y archivos de forma segura y fácil de usar. Esta herramienta le permite explorar carpetas y archivos en un servidor sin necesidad de dependencias externas, al tiempo que proporciona un alto nivel de privacidad.
## Características

- **Navegación con ruta de navegación**: salte fácilmente entre directorios.
- **Secure Path Handling**: Protección contra ataques de cruce de directorios.
- **Listas de ignorar personalizables**: oculta archivos y directorios específicos.
- **Recursos locales**: Bootstrap y Font Awesome se proporcionan localmente.
- **Recarga dinámica**: el contenido de la carpeta se carga a través de AJAX.
## Requisitos del sistema

- PHP a partir de la versión 7.4 (recomendado: PHP 8.0+).
- Servidor web con soporte PHP (por ejemplo, Apache, Nginx, Lighttpd).
- Módulos PHP: php-mbstring, php-json, php-xml, php-curl, php-fileinfo, php-ctype, php-iconv
- Permisos de escritura y lectura para el directorio de destino.
## instalación

1. **Repositorio de clones:**
   ```bash
   git clone https://github.com/dbt1/tuxbox-explorer && cd tuxbox-explorer
   ```
2. **Ajustar configuración:**
   - Cambie el nombre de `config/config-sample.php` a `config/config.php`.
   - Si es necesario, ajuste las listas en `config.php` para mostrar u ocultar archivos y carpetas específicos.
     Utilice listas de ignorar y permitir para especificar de manera flexible las carpetas de datos en las que se encuentran los archivos alojados que deberían estar disponibles.
   - Si es necesario, ajuste las etiquetas y los textos de las ventanas en `config.php`.
3. **Cargue todo el contenido del repositorio clonado a un servidor habilitado para PHP.**
4. **Inicio:**
   Abra `index.php` en el navegador.
## usar

- **Examinar carpeta:**
  Haga clic en las carpetas para ver su contenido.
- **Navegación de ruta de navegación:**
  Utilice la barra de ruta de navegación para pasar rápidamente a directorios de nivel superior.
## Notas de configuración

El archivo `config.php` permite ajustar los siguientes parámetros:

- **ROOT\_PATH**: Ruta absoluta que sirve como directorio raíz.
- **IGNORE\_DIR\_PATTERNS**: Directorios que se ignorarán (por ejemplo, `.git`, `node_modules`).
- **IGNORE\_FILE\_PATTERNS**: Archivos que se ignorarán (por ejemplo, `*.log`, `*.html`).
- **ALLOW\_DIR\_PATTERNS**: Directorios que deben mostrarse a pesar de la lista de ignorados.
- **ALLOW\_FILE\_PATTERNS**: Archivos que deben mostrarse a pesar de la lista de ignorados.
- **DIRECTORIO\_ALIASES**: establece nombres para mostrar para directorios.

Para información sobre protección de datos:

- **DATOS\_CONTROLLER\_NOMBRE**
- **DATOS\_CONTROLLER\_DIRECCIÓN**
- **DATOS\_CONTROLLER\_EMAIL**
- **DATOS\_CONTROLLER\_EMAIL\_ALIAS**
## Recursos utilizados

Este repositorio proporciona copias locales de los siguientes recursos para permitir su uso sin dependencias externas y proteger la privacidad:

- **Bootstrap v5.3.0**

  -CSS: `bootstrap.min.css`
  -JS: `bootstrap.bundle.min.js`
  - Fuente: [https://getbootstrap.com](https://getbootstrap.com)

- **Fuente impresionante v6.4.0**

  -CSS: `all.min.css`
  -JS: `all.min.js`
  - Fuente: [https://fontawesome.com](https://fontawesome.com)
## Licencia

Este proyecto está bajo la licencia MIT. Para obtener más información, consulte [LICENCIA](./LICENCIA).
### Notas sobre los recursos utilizados:

- **Bootstrap**: licencia MIT. Detalles: [Licencia Bootstrap](https://github.com/twbs/bootstrap/blob/main/LICENSE).
- **Font Awesome**: Varias licencias:
  - Iconos: CC BY 4.0
  - Fuentes: Licencia de fuente abierta SIL 1.1
  - Código: licencia MIT

Tenga en cuenta los términos de licencia respectivos de los recursos mencionados anteriormente.
