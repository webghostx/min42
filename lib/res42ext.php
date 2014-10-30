<?php

class res42ext extends res42 {

      public static function getGeneratedCSSMinFile($file, $vars = array()) {

            $fileExtension = parent::getFileExtension($file);

            if (parent::isHttpAddress($file) || $fileExtension === 'css')
                  return $file;


            $file = self::getCompiledCSSFile($file, $fileExtension, $vars);


            return parent::$cssDir . parent::getFileWithVersionParam($file, parent::$cssPath);
      }

      public static function getCombinedCSSMinFile($combinedFile, $sourceFiles) {
            self::combineFiles($combinedFile, parent::$cssPath, $sourceFiles);

            return self::getCSSFile($combinedFile);
      }

      public static function getCombinedJSMinFile($combinedFile, $sourceFiles) {
            self::combineFiles($combinedFile, parent::$jsPath, $sourceFiles);

            return parent::getJSFile($combinedFile);
      }

      protected static function getMinifiedContent($content, $fileExtension) {

            if ($fileExtension == 'js') {
                  // js
                  require_once ('JSMinPlus.php');
                  $content = JSMinPlus::minify($content);
            } elseif ($fileExtension == 'css') {
                  // css
                  require_once ('Compressor.php');
                  $content = Minify_CSS_Compressor::process($content);
                  
            }


            return $content;
      }

      public static function getCSSFile($file, $vars = array()) {
            if (parent::isHttpAddress($file)) {
                  return $file;
            } else {
                  $fileExtension = parent::getFileExtension($file);

                  if ($fileExtension == 'scss' || $fileExtension == 'less') {
                        $file = self::getCompiledCSSFile($file, $fileExtension, $vars);
                  }

                  return parent::$cssDir . parent::getFileWithVersionParam($file, parent::$cssPath);
            }
      }

      protected static function combineFiles($combinedFile, $filePath, $sourceFiles = array()) {
            $combinedFileContent = '';
            $combinedFileWithPath = $filePath . $combinedFile;
            $combinedFileMTime = @filemtime($combinedFileWithPath);
            $doCombine = false;
            $hashString = '';

            // get hash string first
            foreach ($sourceFiles as $file) {
                  $hashString .= $file;
            }

            // check if combined file needs to be created
            if ($combinedFileMTime == false) {
                  // combined file does not exist
                  $doCombine = true;
            } else {
                  // check filemtime of source files
                  foreach ($sourceFiles as $file) {
                        $fileWithPath = $filePath . $file;
                        $fileMTime = @filemtime($fileWithPath);

                        if ($combinedFileMTime == false || $fileMTime > $combinedFileMTime) {
                              // filemtime of one of the source files is newer then of combined file
                              $doCombine = true;
                              break;
                        }
                  }

                  // check resource id inside combined file (when user changed function arguments for example)
                  $fileHandle = @fopen($combinedFileWithPath, 'r');
                  $firstLine = '';

                  if ($fileHandle != false) {
                        $firstLine = fgets($fileHandle);
                        fclose($fileHandle);
                  }

                  $hashStringFromCombinedFile = str_replace('res_id', '', $firstLine);
                  $hashStringFromCombinedFile = trim($hashStringFromCombinedFile, " \t\r\n:*/");

                  if (parent::isValidMd5($hashStringFromCombinedFile) && $hashStringFromCombinedFile != md5($hashString)) {
                        $doCombine = true;
                  }
            }

            // combine files if necessary
            if ($doCombine) {
                  foreach ($sourceFiles as $file) {
                        $fileWithPath = $filePath . $file;

                        // compile first if scss/less
                        $fileExtension = parent::getFileExtension($file);

                        if ($fileExtension == 'scss' || $fileExtension == 'less') {
                              $compiledCSS = self::getCompiledCSSFile($file, $fileExtension);
                              $fileWithPath = $filePath . $compiledCSS;
                        }

                        // now combine
                        if (file_exists($fileWithPath)) {
                              $combinedFileContent .= file_get_contents($fileWithPath) . PHP_EOL . PHP_EOL;
                        }
                  }

                  /*
                   * min
                   */
                  $combinedFileContent = self::getMinifiedContent($combinedFileContent, $fileExtension);

                  // add hash
                  $combinedFileContent = '/* res_id: ' . md5($hashString) . ' */' . PHP_EOL . PHP_EOL . $combinedFileContent;

                  // write combined file
                  $fileHandle = @fopen($combinedFileWithPath, 'w');

                  if ($fileHandle != false) {
                        fwrite($fileHandle, $combinedFileContent);
                        fclose($fileHandle);
                  }
            }
      }

      protected static function getCompiledCSSFile($sourceFile, $sourceFileType, $vars = array()) {

            $cssFile = parent::replaceFileExtension($sourceFile, 'css');
            $sourceFileWithPath = parent::$cssPath . $sourceFile;
            $cssFileWithPath = parent::$cssPath . $cssFile;

            $cssFileMTime = @filemtime($cssFileWithPath);
            $sourceFileMTime = @filemtime($sourceFileWithPath);


            if ($cssFileMTime == false || $sourceFileMTime > $cssFileMTime) {
                  // compile scss
                  self::compileCSS($sourceFileWithPath, $cssFileWithPath, $sourceFileType, $vars);
            }

            // return css file
            return $cssFile;
      }

      protected static function compileCSS($sourceFileWithPath, $cssFileWithPath, $sourceFileType, $vars) {
            global $REX;


            if (!file_exists($sourceFileWithPath)) {
                  return;
            }

            // get content of source file
            $sourceFileContent = file_get_contents($sourceFileWithPath);

            // strip comments out
            $sourceFileContent = parent::stripCSSComments($sourceFileContent);

            // get file path
            $path = pathinfo($sourceFileWithPath);

            // compile source file to css
            try {
                  if ($sourceFileType == 'scss') {
                        // EP for scss string compilation
                        $compiledCSS = rex_register_extension_point('SEO42_COMPILE_SCSS', $sourceFileContent, array('vars' => $vars));

                        if ($sourceFileContent == $compiledCSS) {
                              // include compiler
                              if (!class_exists('scssc')) {
                                    require_once($REX['INCLUDE_PATH'] . '/addons/seo42/classes/class.scssc.inc.php');
                              }

                              $formatter = new scss_formatter;
                              $formatter->indentChar = "\t";
                              $formatter->close = "}" . PHP_EOL;
                              $formatter->assignSeparator = ": ";

                              $scss = new scssc();
                              $scss->setFormatter($formatter);

                              $compiledCSS = $scss->compile($sourceFileContent);
                        }
                  } else {
                        // EP for less string compilation
                        $compiledCSS = rex_register_extension_point('SEO42_COMPILE_LESS', $sourceFileContent, array('vars' => $vars, 'path' => $path['dirname']));

                        if ($sourceFileContent == $compiledCSS) {
                              // include compiler
                              if (!class_exists('lessc')) {
                                    require_once($REX['INCLUDE_PATH'] . '/addons/seo42/classes/class.lessc.inc.php');
                              }

                              $formatter = new lessc_formatter_classic;
                              $formatter->indentChar = "\t";
                              $formatter->close = "}" . PHP_EOL;
                              $formatter->assignSeparator = ": ";

                              $less = new lessc();
                              $less->setImportDir($path['dirname']);
                              $less->setFormatter($formatter);
                              $less->setPreserveComments(true);
                              $less->setVariables($vars);

                              $compiledCSS = $less->compile($sourceFileContent);
                        }
                  }
            } catch (Exception $e) {
                  echo '" />'; // close tag as we are probably in an open link tag in head section of website 
                  echo '<p style="margin: 5px;"><code>';
                  echo '<strong>' . strtoupper($sourceFileType) . ' Compile Error:</strong><br/>';
                  echo $e->getMessage();
                  echo '</code></p>';
                  exit;
            }


            /*
             * min
             */
            $compiledCSS = self::getMinifiedContent($compiledCSS, 'css');

            // write css
            $fileHandle = fopen($cssFileWithPath, 'w');
            fwrite($fileHandle, $compiledCSS);
            fclose($fileHandle);
            #file_put_contents($cssFileWithPath, $compiledCSS);
      }

}
