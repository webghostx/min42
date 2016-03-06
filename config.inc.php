<?php

/**
 * min42
 *
 * @version 1.0
 * @package REDAXO seo42 3.4.0 + / PHP 5.3.2 +
 * @author webghost
 * @license Copyright (c) 2014 webghost (MIT License)
 */

$addonKey = 'min42';
define("MIN42_PATH", $REX['INCLUDE_PATH'] . '/addons/seo42/plugins/' . $addonKey);
define("MIN42_DATA_PATH", MIN42_PATH . '/data');

/*
 * Plugin initialisieren
 */
$REX['ADDON']['rxid'][$addonKey] = '1204';
#$REX['ADDON']['page'][$addonKey] = $addonKey;
#$REX['ADDON']['name'][$addonKey] = 'Minifier';
$REX['ADDON']['version'][$addonKey] = '1.0.2';
$REX['ADDON']['author'][$addonKey] = 'webghost';
$REX['ADDON']['supportpage'][$addonKey] = 'https://github.com/webghostx/minifier/issues';
$REX['PERM'][] = 'min42[]';

//
if ($REX['SETUP'])
      goto end;

/*
 * autoloader
 */
spl_autoload_register(function ($className) {

      $folder = MIN42_PATH . '/lib/';
      $subFolder = MIN42_PATH . '/lib/vendor/';
      $file = $className . '.php';

      if (is_readable($folder . $file)) {
            require_once $folder . $file;
      } elseif (is_readable($subFolder . $file)) {
            require_once $subFolder . $file;
      }
});


/*
 * Einstellungen laden
 */
$REX['ADDON'][$addonKey]['conf'] = min42::loadSettings();

/*
 * HTML minify
 */
if (!$REX['REDAXO'])
      min42::minifyHTML();


if ($REX['REDAXO'])
	#$I18N->appendFile(MIN42_PATH . '/lang/');

//
end:
