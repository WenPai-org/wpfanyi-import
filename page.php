<?php
/**
 * Translation package import HTML
 *
 * @package wpfanyi-import
 */

defined('ABSPATH') || exit;
?>
<div class="wrap">
  <h1><?php esc_html_e('Import translation', 'wpfanyi-import'); ?></h1>
  <div>
    <div class="notice notice-info">
      <p>
          <?php esc_html_e('The translation pack is a Zip package including MO and PO files. Select the translation pack on this page and set its type correctly then click Import to add it to WordPress.', 'wpfanyi-import'); ?>
        <br/>
        <b><?php esc_html_e('Note: If a translation package with the same name already exists, this operation will overwrite it', 'wpfanyi-import'); ?></b>
      </p>
    </div>
    <div class="main">
      <div class="function">

        <ul class="wpfanyi-tab-title">
          <li class="wpfanyi-this"><?php esc_html_e('Import from Local', 'wpfanyi-import'); ?></li>
          <li><?php esc_html_e('Import from URL', 'wpfanyi-import'); ?></li>
        </ul>

        <div class="wpfanyi-tab-content">
          <div class="wpfanyi-tab-item wpfanyi-show">
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('wpfanyi-import-nonce'); ?>
              <input type="hidden" name="trans_import_method" value="file">
              <table class="form-table" role="presentation">
                <tbody>
                <tr>
                  <th><?php esc_html_e('Translation package:', 'wpfanyi-import'); ?></th>
                  <td>
                    <label>
                      <input type="file" name="trans_zip"/ accept=".zip">
                    </label>
                  </td>
                </tr>
                <tr>
                  <th><?php esc_html_e('Package type:', 'wpfanyi-import'); ?></th>
                  <td>
                    <label>
                      <input type="radio" name="trans_type" value="plugin" checked/>
                        <?php esc_html_e('Plugin', 'wpfanyi-import'); ?></label>
                    <label>
                      <input type="radio" name="trans_type" value="theme"/>
                        <?php esc_html_e('Theme', 'wpfanyi-import'); ?></label>
                  </td>
                </tr>
                </tbody>
              </table>
              <p class="submit">
                <input type="submit" name="submit" class="button-primary"
                       value="<?php esc_html_e('Import', 'wpfanyi-import'); ?>"/>
              </p>
            </form>
          </div>
          <div class="wpfanyi-tab-item">
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('wpfanyi-import-nonce'); ?>
              <input type="hidden" name="trans_import_method" value="url">
              <table class="form-table" role="presentation">
                <tbody>
                <tr>
                  <th scope="row"><?php esc_html_e('URL address:', 'wpfanyi-import'); ?></th>
                  <td>
                    <div style="display:inline-block;position:relative;">
                      <div class="input_clear">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                          ×
                        </button>
                      </div>
                      <input name="trans_url" type="url" id="trans_url" value="" class="trans_url"
                             placeholder="https://wpfanyi.com/glotpress/bulk-export/wordfence/">
                    </div>
                  </td>
                </tr>
                <tr>
                  <th><?php esc_html_e('Package type:', 'wpfanyi-import'); ?></th>
                  <td>
                    <label>
                      <input type="radio" name="trans_type" value="plugin" checked/>
                        <?php esc_html_e('Plugin', 'wpfanyi-import'); ?></label>
                    <label>
                      <input type="radio" name="trans_type" value="theme"/>
                        <?php esc_html_e('Theme', 'wpfanyi-import'); ?></label>
                  </td>
                </tr>
                </tbody>
              </table>
              <p class="submit">
                <input type="submit" name="submit" class="button-primary"
                       value="<?php esc_html_e('Import', 'wpfanyi-import'); ?>"/>
              </p>
            </form>
          </div>
        </div>
      </div>
      <div class="wpfanyi-import-config-help stuffbox">
        <button type="button" class="handlediv" aria-expanded="true"><span class="toggle-indicator" aria-hidden="true"></span></button>
        <h2 class="handle"><?php esc_html_e('Help', 'wpfanyi-import'); ?></h2>
        <div class="inside">
          <h2><?php esc_html_e('This plugin provides an innovative translation package installation method.', 'wpfanyi-import'); ?></h2>
          <ol>
            <li>
              <b><?php esc_html_e('Old Method', 'wpfanyi-import'); ?></b>
              <p><?php esc_html_e('You need to download the translation package to the local first, unzip the package, and upload it to the correct path via FTP.', 'wpfanyi-import'); ?><br/>
                  <?php esc_html_e('Imagine the process of selecting a lengthy path on FTP with the mouse. This is troublesome, right?', 'wpfanyi-import'); ?></p>
            </li>
            <li>
              <b><?php esc_html_e('New Method', 'wpfanyi-import'); ?></b>
              <p><?php esc_html_e('Now you only need to paste the download address of the translation package in the text input, and correctly select the type of translation package (plugin or theme), after clicking “Import”, the tool will help you complete all operations.', 'wpfanyi-import'); ?><br/>
                  <?php esc_html_e('If you have a local translation package, you can also easily import it through this tool.', 'wpfanyi-import'); ?></p>
            </li>
          </ol>
          <h2><?php esc_html_e('Need to translate a WordPress plugin/theme?', 'wpfanyi-import'); ?></h2>
          <?php /* translators: %s: https://wpfanyi.com/new-project */ ?>
          <p><?php printf(__('If he is hosted in wordpress.org we will handle its translation for free! Please send your needs to: <a href="%s" target="_blank">https://wpfanyi.com/new-project</a>.', 'wpfanyi-import'), 'https://wpfanyi.com/new-project'); ?></p>
        </div>
      </div>
    </div>
  </div>
</div>
