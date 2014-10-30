<?php

/**
 * seo42 Minifier
 *
 * @version 0.1
 * @author webghost
 */
$page = 'minifier';

// Config
$REX['ADDON']['rxid'][$page] = '1204';
$REX['ADDON']['page'][$page] = $page;
#$REX['ADDON']['name'][$page] = '';
$REX['ADDON']['version'][$page] = '0.1';
$REX['ADDON']['author'][$page] = 'webghost';
$REX['ADDON']['supportpage'][$page] = 'forum.redaxo.de';

if (!$REX['SETUP']) {

      require_once (__DIR__ . '/lib/res42ext.php');
      require_once (__DIR__ . '/lib/seo42ext.php');
}
