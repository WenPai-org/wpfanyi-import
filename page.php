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
        <b><?php esc_html_e('Note: If a translation package with the same name already exists, this operation will overwrite it', 'wpfanyi-import'); ?></b>
      </p>
    </div>
    <div class="main">
      <div class="function">

        <ul class="layui-tab-title">
          <li class="layui-this"><?php esc_html_e('Import from Local', 'wpfanyi-import'); ?></li>
          <li><?php esc_html_e('Import from URL', 'wpfanyi-import'); ?></li>
        </ul>

        <div class="layui-tab-content">
          <div class="layui-tab-item layui-show">
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
          <div class="layui-tab-item">
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
      <div class="help">
        <div class="layui-collapse" lay-accordion>
          <div class="layui-colla-item">
            <h2 class="layui-colla-title">一、通用手动安装</h2>
            <div class="layui-colla-content layui-show"> 1.&nbsp;安装插件语言包<br />
              将下载到的插件语言包&nbsp;<code>.zip</code>&nbsp;文件解压后上传至路径为您的网站<code>/wp-content/languages/plugins</code>即可。<br />
              2.&nbsp;安装主题语言包<br />
              将下载到的主题语言包&nbsp;<code>.zip</code>&nbsp;文件解压后上传至路径为您的网站<code>/wp-content/languages/themes</code>即可。<br />
              3.&nbsp;其他安装方式<br />
              除前两种方式外，也可以直接在插件或主题的安装文件夹内查找如&nbsp;<code>languages&nbsp;local&nbsp;lang&nbsp;i18n</code>&nbsp;放入对应的目录中。</div>
          </div>
          <div class="layui-colla-item">
            <h2 class="layui-colla-title"> 二、文派插件安装</h2>
            <div class="layui-colla-content"> 1、上传安装<br />
              通过直接上传您已下载到的<code>XXX_zh_CN.zip</code>&nbsp;语言包文件，选择对应的语言类型【插件】或【主题】点击导入，即可完成。<br />
              2、在线安装<br />
              前往文派翻译（<a href="wpfanyi.com" target="_blank" rel="noopener">wpfanyi.com</a>）获取到语言包地址，填入在线安装地址，选择【插件】或【主题】点击导入，即可完成。</div>
          </div>
          <div class="layui-colla-item">
            <h2 class="layui-colla-title">三、中文翻译请求</h2>
            <div class="layui-colla-content">1、用户中文翻译请求<br />
              &nbsp;如果您是WordPress插件主题作者，并希望将自己喜欢的产品翻译至中文，请通过&nbsp;<a href="https://wpfanyi.com/new-project" target="_blank" rel="noopener">https://wpfanyi.com/new-project</a>&nbsp;提交信息，我们将无偿处理。<br />
              2、作者中文翻译请求<br />
              如果您是WordPress开发者，并希望将自己的产品翻译至中文，寻求中文贡献者，请邮件至&nbsp;<a href="mailto:wpfanyi@feibisi.com" target="_blank" rel="noopener">wpfanyi@feibisi.com</a>&nbsp;我们中文翻译团队，将会无偿处理。（仅限&nbsp;WordPress.org&nbsp;免费产品，如需长期贡献者，请为&nbsp;<code>@wpfanyi</code>&nbsp;添加&nbsp;PET&nbsp;翻译权限）</p></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>