minifier
========
https://github.com/RexDude/seo42

Damit können minifizierte CSS/JS Dateien generiert werden

Installation
============
* Das Verzeichnit "minifier in den Plugin-Ordner im Addon seo42 ablegen
* In REDAXO unter Addons das Plugin installieren und aktivieren

Anwendungsbeispiele
===================
Nun stehen 3 weitere Methoden zur Verfügung in der abgeleiteten Klasse seo42ext

LESS/SCSS minifiziert ausgeben lassen:

   <link rel="stylesheet" href="<?php echo seo42ext::getGeneratedCSSMinFile("theme.less"); ?>">
