minifier
========
Plugin für seo42 https://github.com/RexDude/seo42

Damit können minifizierte CSS/JS Dateien generiert werden

Installation
------------
* Das Verzeichnis "minifier" in den Plugin-Ordner im Addon seo42 ablegen
* In REDAXO unter Addons das Plugin installieren und aktivieren

Anwendungsbeispiele
-------------------
Nun stehen 3 weitere Methoden zur Verfügung in der abgeleiteten Klasse seo42ext

LESS/SCSS Datei als minifizierte CSS ausgeben lassen:
```html
<link rel="stylesheet" href="<?php echo seo42ext::getGeneratedCSSMinFile("theme.less"); ?>">
```

Mehrer CSS Dateien minifiziert ausgeben lassen:
```html
<script type="text/javascript" src="<?php
    echo seo42ext::getCombinedCSSMinFile("default.js", array(
        "reset.css",
        "theme.css",
        "classes.css"
    ));
?>"></script>
```

Mehrer JS Dateien minifiziert ausgeben lassen:
```html
<script type="text/javascript" src="<?php
    echo seo42ext::getCombinedJSMinFile("default.js", array(
        "jquery-1.11.1.min.js",
        "bootstrap.min.js",
        "jQueryExtension.js",
        "basic.js"
    ));
?>"></script>
```
