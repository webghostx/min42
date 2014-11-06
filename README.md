min42
========
Plugin für seo42 https://github.com/RexDude/seo42

Dieses Plugin ergänzt die Funktionen zur Ausgabe von CSS und JS Dateien in seo42 um eine

Damit können im Template minifizierte CSS/JS Dateien aufgerufen werden. Funktioniert auch mit kombinierten Dateien.
Zusätzlich kann der gesamte HTML Quellcode der Webseite minifiziert ausgegeben werden.

Installation
------------
* Das Verzeichnis "min42" in den Plugin-Ordner im Addon seo42 ablegen
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
<link rel="stylesheet" href="<?php
    echo seo42ext::getCombinedCSSMinFile("default.css", array(
        "reset.css",
        "theme.css",
        "classes.css"
    ));
?>">
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

Einstellungen
-------------
Im Backend unter SEO42 -> Minifier kann man ein paar Einstellungen vornehmen