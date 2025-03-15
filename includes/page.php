<?php
/**
 * Translation package import HTML
 *
 * @package wpfanyi-import
 */
defined('ABSPATH') || exit;
?>
<div class="wrap">
    <h1><?php esc_html_e('Import Translation', 'wpfanyi-import'); ?></h1>
    <?php settings_errors('wpfanyi_import_messages'); ?>

    <h2 class="nav-tab-wrapper">
        <a href="#local" class="nav-tab nav-tab-active"><?php esc_html_e('Import from Local', 'wpfanyi-import'); ?></a>
        <a href="#url" class="nav-tab"><?php esc_html_e('Import from URL', 'wpfanyi-import'); ?></a>
        <a href="#formats" class="nav-tab"><?php esc_html_e('Package Formats', 'wpfanyi-import'); ?></a>
        <a href="#settings" class="nav-tab"><?php esc_html_e('Settings', 'wpfanyi-import'); ?></a>
        <a href="#help" class="nav-tab"><?php esc_html_e('Help', 'wpfanyi-import'); ?></a>
    </h2>

    <div id="local" class="tab-content">
        <p class="description"><?php esc_html_e('Upload a ZIP file containing translation files.', 'wpfanyi-import'); ?> <strong><?php esc_html_e('Note: Existing packages with the same name will be overwritten.', 'wpfanyi-import'); ?></strong></p>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('wpfanyi-import-nonce'); ?>
            <input type="hidden" name="trans_import_method" value="file">
            <table class="form-table">
                <tr>
                    <th><?php esc_html_e('Translation package:', 'wpfanyi-import'); ?></th>
                    <td><input type="file" name="trans_zip" accept=".zip"></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Package type:', 'wpfanyi-import'); ?></th>
                    <td><?php $this->render_radio_options(); ?></td>
                </tr>
            </table>
            <?php submit_button(__('Import', 'wpfanyi-import')); ?>
        </form>
    </div>

    <div id="url" class="tab-content" style="display: none;">
        <p class="description"><?php esc_html_e('Provide a URL to a ZIP file containing translation files.', 'wpfanyi-import'); ?> <strong><?php esc_html_e('Note: Existing packages with the same name will be overwritten.', 'wpfanyi-import'); ?></strong></p>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('wpfanyi-import-nonce'); ?>
            <input type="hidden" name="trans_import_method" value="url">
            <table class="form-table">
                <tr>
                    <th><?php esc_html_e('URL address:', 'wpfanyi-import'); ?></th>
                    <td><input type="url" name="trans_url" id="trans_url" value="" placeholder="https://example.com/translation.zip"></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Package type:', 'wpfanyi-import'); ?></th>
                    <td><?php $this->render_radio_options(); ?></td>
                </tr>
            </table>
            <?php submit_button(__('Import', 'wpfanyi-import')); ?>
        </form>
    </div>

    <div id="formats" class="tab-content" style="display: none;">
        <div class="card">
            <h2><?php esc_html_e('Supported Package Formats', 'wpfanyi-import'); ?></h2>
            <p><?php esc_html_e('Your translation package must follow one of these structures to be recognized by the plugin.', 'wpfanyi-import'); ?></p>
            <details open>
                <summary><?php esc_html_e('Plugin Package Format', 'wpfanyi-import'); ?></summary>
                <p><?php esc_html_e('For translating plugins, use this structure:', 'wpfanyi-import'); ?></p>
                <pre>
bbpress-zh_CN.zip
├── bbpress-zh_CN.mo
└── bbpress-zh_CN.po
                </pre>
            </details>
            <details>
                <summary><?php esc_html_e('Theme Package Format', 'wpfanyi-import'); ?></summary>
                <p><?php esc_html_e('For translating themes, use this structure:', 'wpfanyi-import'); ?></p>
                <pre>
twentytwentyone-zh_CN.zip
├── twentytwentyone-zh_CN.mo
└── twentytwentyone-zh_CN.po
                </pre>
            </details>
            <details>
                <summary><?php esc_html_e('Auto-Detect Package Format', 'wpfanyi-import'); ?></summary>
                <p><?php esc_html_e('For mixed translations, organize them like this:', 'wpfanyi-import'); ?></p>
                <pre>
translation-zh_CN.zip
├── plugins/
│   ├── bbpress-zh_CN.mo
│   └── bbpress-zh_CN.po
└── themes/
    ├── twentytwentyone-zh_CN.mo
    └── twentytwentyone-zh_CN.po
                </pre>
            </details>
        </div>
    </div>

    <div id="settings" class="tab-content" style="display: none;">
        <div class="card">
            <h2><?php esc_html_e('Settings', 'wpfanyi-import'); ?></h2>
            <p><?php esc_html_e('Manage plugin settings and clean up temporary files.', 'wpfanyi-import'); ?></p>
            <form method="post">
                <?php wp_nonce_field('wpfanyi-clean-temp-nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th><?php esc_html_e('Clean Temporary Files', 'wpfanyi-import'); ?></th>
                        <td>
                            <p class="description"><?php esc_html_e('Remove temporary JSON files (e.g., zh_CN-*.json) from wp-content/languages.', 'wpfanyi-import'); ?></p>
                            <?php submit_button(__('Clean Now', 'wpfanyi-import'), 'secondary', 'clean_temp_files'); ?>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <div id="help" class="tab-content" style="display: none;">
        <div class="card">
            <h2><?php esc_html_e('Help & Troubleshooting', 'wpfanyi-import'); ?></h2>
            <p><?php esc_html_e('Having trouble? Here are solutions to common issues and ways to get support.', 'wpfanyi-import'); ?></p>
            
            <h3><?php esc_html_e('Why isn’t my translation working?', 'wpfanyi-import'); ?></h3>
            <ul>
                <li><?php esc_html_e('Check the package: Ensure it contains a valid .mo file (compiled translation). The .po file alone won’t work.', 'wpfanyi-import'); ?></li>
                <li><?php esc_html_e('Verify the type: Did you select the correct "Package type" (Plugin, Theme, or Auto)? Mismatched types can cause issues.', 'wpfanyi-import'); ?></li>
                <li><?php esc_html_e('Test manually: Upload the files to wp-content/languages/plugins or wp-content/languages/themes using FTP to confirm they work.', 'wpfanyi-import'); ?></li>
            </ul>

            <h3><?php esc_html_e('What if the import fails?', 'wpfanyi-import'); ?></h3>
            <ul>
                <li><?php esc_html_e('File upload issues: Ensure your server allows file uploads and the ZIP isn’t corrupted.', 'wpfanyi-import'); ?></li>
                <li><?php esc_html_e('URL problems: Verify the URL is correct and accessible. It must point to a valid ZIP file.', 'wpfanyi-import'); ?></li>
                <li><?php esc_html_e('Permissions: Check if wp-content/languages is writable by your server.', 'wpfanyi-import'); ?></li>
            </ul>

            <h3><?php esc_html_e('Need more help?', 'wpfanyi-import'); ?></h3>
            <p><?php printf(__('Report bugs or ask questions on <a href="%s" target="_blank">GitHub Issues</a>. We’ll respond and improve the plugin!', 'wpfanyi-import'), 'https://github.com/WenPai-org/wpfanyi-import/issues'); ?></p>
            <p><?php printf(__('Want a plugin or theme translated? Submit a request at <a href="%s" target="_blank">WPfanyi Project</a>. Free for WordPress.org-hosted projects!', 'wpfanyi-import'), 'https://wpfanyi.com/new-project'); ?></p>
        </div>
    </div>
</div>