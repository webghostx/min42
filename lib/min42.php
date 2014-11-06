<?php

/**
 * min42
 *
 * @version 1.0
 * @package REDAXO seo42 3.4.0 +
 * @author webghost
 * @license Copyright (c) 2014 webghost (MIT License)
 */


class min42 {

      const defaultFilePrefix = 'default_';
      const defaultSettingsFile = 'settings.ini';

      /*
       * ***************************************************************************************************************
       * öffentliche Methoden
       */

      /**
       * Gibt eine LESS/SCSS Datei kompiliert und minifiziert zurück
       * 
       * @global array $REX
       * @param string $file - Die auszugebene Datei
       * @param array $vars
       * @return string - Dateiname inkl. Pfad
       */
      public static function getGeneratedCSSMinFile($file, $vars = array()) {
            global $REX;

            if (self::checkINIBoolValue($REX['ADDON']['min42']['conf']['minifyCSS']))
                  return res42min::getGeneratedCSSMinFile($file, $vars);

            return res42::getCSSFile($file, $vars);
      }

      /**
       * Gibt mehrere CSS Dateien kombiniert und minifiziert zurück
       * 
       * @global array $REX
       * @param string $combinedFile - der Name der zu erstellenden Datei
       * @param array $sourceFiles - ein Array mit allen Quelldateien
       * @return string - Dateiname inkl. Pfad zur generierten Datei
       */
      public static function getCombinedCSSMinFile($combinedFile, $sourceFiles) {
            global $REX;

            if (self::checkINIBoolValue($REX['ADDON']['min42']['conf']['minifyCSS']))
                  return res42min::getCombinedCSSMinFile($combinedFile, $sourceFiles);

            return res42::getCombinedCSSFile($combinedFile, $sourceFiles);
      }

      /**
       * Gibt mehrere JS Dateien kombiniert und minifiziert zurück
       * 
       * @global array $REX
       * @param string $combinedFile - der Name der zu erstellenden Datei
       * @param array $sourceFiles - ein Array mit allen Quelldateien
       * @return string - Dateiname inkl. Pfad zur generierten Datei
       */
      public static function getCombinedJSMinFile($combinedFile, $sourceFiles) {
            global $REX;

            if (self::checkINIBoolValue($REX['ADDON']['min42']['conf']['minifyJS']))
                  return res42min::getCombinedJSMinFile($combinedFile, $sourceFiles);

            return res42::getCombinedJSFile($combinedFile, $sourceFiles);
      }

      /*
       * ***************************************************************************************************************
       * interne Methoden
       */

      protected static function checkINIBoolValue($val) {

            if ($val === 1 || $val === true || $val === '1' || $val == 'true' || $val == 'on') {
                  return true;
            } elseif ($val === 0 || $val === false || $val === '0' || $val == 'false' || $val == 'off') {
                  return false;
            }
            
            return NULL;
      }

      /**
       * gibt die Einstellungen in einem Array zurück
       * 
       * @return array
       */
      public static function loadSettings() {

            $array = @parse_ini_file(MIN42_DATA_PATH . '/' . self::defaultSettingsFile);

            if (!$array)
                  $array = @parse_ini_file(MIN42_DATA_PATH . '/' . self::defaultFilePrefix . self::defaultSettingsFile);

            return $array;
      }

      /**
       * Lässt über den Output-Filter die HTML-Ausgabe minifizieren unter berücksichtigung der Settings
       * 
       * @global array $REX
       * @return
       */
      public static function minifyHTML() {
            global $REX;

            if (self::checkINIBoolValue($REX['ADDON']['min42']['conf']['minifyHTML'])) {

                  rex_register_extension('OUTPUT_FILTER', function ($params) {
                        global $REX;

                        $content = $params['subject'];
                        $minifiedContent = minHTML::minify($content, array(
                                    'cssMinifier' => ($REX['ADDON']['min42']['conf']['minifyEmbedCSS'] === true) ? 'minCSS::minify' : false,
                                    'jsMinifier' => ($REX['ADDON']['min42']['conf']['minifyEmbedJS'] === true) ? 'minJS::minify' : false,
                                    'xhtml' => false
                        ));
                        return $minifiedContent;
                  }, array(), REX_EXTENSION_LATE);
            }
            return;
      }

      /**
       * gibt die Einstellungen aus
       * 
       * @return string
       */
      public static function getSettings() {

            $ini = @file_get_contents(MIN42_DATA_PATH . '/' . self::defaultSettingsFile);

            if (!$ini)
                  $ini = @file_get_contents(MIN42_DATA_PATH . '/' . self::defaultFilePrefix . self::defaultSettingsFile);

            return $ini;
      }

      /**
       * speichert die Einstellungen
       * 
       * @return bool
       */
      public static function setSettings($content) {

            $ini = @file_put_contents(MIN42_DATA_PATH . '/' . self::defaultSettingsFile, $content);

            if (!$ini)
                  return false;

            return true;
      }

      /*
       * ***************************************************************************************************************
       * Tools
       */

      public static function startTimer() {
            return microtime(true);
      }

      public static function stopTimer($startTime) {
            return 'Laufzeit: ' . (microtime(true) - $startTime) * 1000 . ' sec.';
      }

      public static function var_dump($var) {
            echo "\n\n";
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
            echo "\n\n";
      }

}
