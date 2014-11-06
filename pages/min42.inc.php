<?php

/**
 * min42
 *
 * @version 1.0
 * @package REDAXO seo42 3.4.0 +
 * @author webghost
 * @license Copyright (c) 2014 webghost (MIT License)
 */

$page = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
$settings = rex_request('settings', 'string');
$func = rex_request('func', 'string');

if ($func == "update") {

      $check = min42::setSettings($settings);

      if ($check) {
            echo rex_info($I18N->msg('min42_save_ok'));
      } else {
            echo rex_warning($I18N->msg('min42_save_fail'));
      }
}

if ($func == "reset") {
      
      $settings = file_get_contents(MIN42_DATA_PATH . '/default_settings.ini');
      $check = min42::setSettings($settings);
      
      if ($check) {
            echo rex_info($I18N->msg('min42_reset_ok'));
      } else {
            echo rex_warning($I18N->msg('min42_reset_fail'));
      }
}
?>
<div class="rex-addon-output">
      <h2 class="rex-hl2">Minifier für SEO42</h2>

<?php
if (isset($REX['USER']) && !$REX['USER']->isAdmin() && $REX['USER']->hasPerm('min42[]'))
      goto endSettings;
?>
      <div class="rex-area-content">
            <h3>Einstellungen</h3>
            <p>
                  <?php echo $I18N->msg('min42_settings_text') ?>
                  
            </p>


            <form action="index.php" method="post" accept-charset="utf-8">

                  <textarea name="settings" 
                            class="codemirror" 
                            data-codemirror-mode="text/x-ini" 
                            style="width: 660px; height: 320px; margin: 0.5em 0"
                            ><?php echo min42::getSettings(); ?></textarea>

                  <input type="hidden" name="page" value="seo42" />
                  <input type="hidden" name="subpage" value="min42" />
                  <input type="hidden" name="func" value="update" />
                  <div class="rex-form-row">
                        <p class="button">
                              <input  type="submit" class="rex-form-submit" name="send" value="<?php echo $I18N->msg('min42_do_settings_save') ?>" />
                        </p>
                  </div>
            </form>

            <div style="float: right">
                  <form action="index.php" method="post">

                        <input type="hidden" name="page" value="seo42" />
                        <input type="hidden" name="subpage" value="min42" />
                        <input type="hidden" name="func" value="reset" />
                        <div class="rex-form-row">
                              <p class="button">
                                    <input type="submit" class="rex-form-submit" name="send" value="<?php echo $I18N->msg('min42_do_settings_reset') ?>"
                                           onClick="return confirm('<?php echo $I18N->msg('min42_confirm_reset') ?>')"
                                           style="border-color: red"
                                           />
                              </p>
                        </div>
                  </form>
            </div>
      </div>
      
<?php endSettings: ?>
      <br /><hr />
      <div class="rex-area-content">
                  <?php readfile(MIN42_PATH . '/help.inc.php') ?>
      </div>
</div>