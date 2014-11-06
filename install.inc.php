<?php

/**
 * min42
 *
 * @version 1.0
 * @package REDAXO seo42 3.4.0 +
 * @author webghost
 * @license Copyright (c) 2014 webghost (MIT License)
 */

$msg = '';
$minPhpVersion = '5.3.3';
$minSeo42Version = '3.4.0';

if (version_compare(PHP_VERSION, $minPhpVersion) < 0) {
      $msg = "PHP Version $minPhpVersion or later required";
} elseif (version_compare($REX['ADDON']['version']['seo42'], $minSeo42Version) < 0) {
      $msg = "SEO42 Version $minSeo42Version or later required";
} else {
      require_once $dir . '/lib/manager.php';
      $msg = rex_developer_manager::checkDir('data/addons/developer');
}

if ($msg != '') {
      $REX['ADDON']['installmsg']['min42'] = $msg;
      $REX['ADDON']['install']['min42'] = FALSE;
} else {
      $REX['ADDON']['install']['min42'] = TRUE;
}
