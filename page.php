<?php
/**
 * 这是翻译包导入页面的HTML
 *
 * @package wpfanyi-import
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap">
  <h1><?php esc_html_e('导入翻译包', 'wpfanyi-import'); ?></h1>
  <div>
    <div class="notice notice-info">
      <p>
          <?php esc_html_e('翻译包是包含.mo和.po文件的zip压缩包，在本页面选择翻译包并正确设置其类型后点击导入即可将其添加到系统中', 'wpfanyi-import'); ?><br/>
        <b><?php esc_html_e('注意：若已存在同名的翻译包则该操作会将其覆盖', 'wpfanyi-import'); ?></b>
      </p>
    </div>
    <ul class="trans_tabs">
      <li tabid="1" class="active"><?php esc_html_e('从本地上传', 'wpfanyi-import'); ?></li>
      <li tabid="2"><?php esc_html_e('从URL导入', 'wpfanyi-import'); ?></li>
    </ul>

    <div id="box">
      <div class="tab-item show">
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('wpfanyi-import-nonce'); ?>
          <input type="hidden" name="trans_import_method" value="file">
          <table class="form-table" role="presentation">
            <tbody>
            <tr>
              <th><?php esc_html_e('翻译包：', 'wpfanyi-import'); ?></th>
              <td>
                <label>
                  <input type="file" name="trans_zip"/ accept=".zip">
                </label>
              </td>
            </tr>
            <tr>
              <th><?php esc_html_e('包类型：', 'wpfanyi-import'); ?></th>
              <td>
                <label>
                  <input type="radio" name="trans_type" value="plugin" checked/>
                      <?php esc_html_e('插件', 'wpfanyi-import'); ?></label>
                <label>
                  <input type="radio" name="trans_type" value="theme"/>
                    <?php esc_html_e('主题', 'wpfanyi-import'); ?></label>
              </td>
            </tr>
            </tbody>
          </table>
          <p class="submit">
            <input type="submit" name="submit" class="button-primary"
                   value="<?php esc_html_e('立即导入', 'wpfanyi-import'); ?>"/>
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
              <th scope="row"><?php esc_html_e('URL地址：', 'wpfanyi-import'); ?></th>
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
              <th><?php esc_html_e('包类型：', 'wpfanyi-import'); ?></th>
              <td>
                <label>
                  <input type="radio" name="trans_type" value="plugin" checked/>
                      <?php esc_html_e('插件', 'wpfanyi-import'); ?></label>
                <label>
                  <input type="radio" name="trans_type" value="theme"/>
                    <?php esc_html_e('主题', 'wpfanyi-import'); ?></label>
              </td>
            </tr>
            </tbody>
          </table>
          <p class="submit">
            <input type="submit" name="submit" class="button-primary"
                   value="<?php esc_html_e('立即导入', 'wpfanyi-import'); ?>"/>
          </p>
        </form>
      </div>
    </div>
  </div>
</div>
