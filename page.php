<?php
/**
 * Translation package import HTML
 *
 * @package wpfanyi-import
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap">
  <h1><?php esc_html_e('Import translation', 'wpfanyi-import'); ?></h1>
  <div>
    <div class="notice notice-info">
      <p>
          <?php esc_html_e('The translation pack is a Zip package including MO and PO files. Select the translation pack on this page and set its type correctly then click Import to add it to WordPress.', 'wpfanyi-import'); ?><br/>
        <b><?php esc_html_e('Note: If a translation package with the same name already exists, this operation will overwrite it ', 'wpfanyi-import'); ?></b>
      </p>
    </div>
    <ul class="trans_tabs">
      <li tabid="1" class="active"><?php esc_html_e('Import from Local', 'wpfanyi-import'); ?></li>
      <li tabid="2"><?php esc_html_e('Import from URL', 'wpfanyi-import'); ?></li>
    </ul>

    <div id="box">
      <div class="tab-item show">
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
      <div class="tab-item">
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('wpfanyi-import-nonce'); ?>
          <input type="hidden" name="trans_import_method" value="url">
          <table class="form-table" role="presentation">
            <tbody>
            <tr>
              <th scope="row"><?php esc_html_e('URL address:', 'wpfanyi-import'); ?></th>
              <td>
				  
<div style="display:inline-block;position:relative;">  
             <div  class="input_clear">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					×
		</button>
	     </div>  
             <input name="trans_url" type="url" id="trans_url" value="" class="trans_url" placeholder="https://wpfanyi.com/glotpress/bulk-export/wordfence/">
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
</div>
