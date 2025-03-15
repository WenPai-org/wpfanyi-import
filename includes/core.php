<?php
/**
 * Class WPfanyi_Import
 *
 * Core class
 *
 * @package wpfanyi-import
 */
class WPfanyi_Import {

    private $trans_import_method = '';
    private $trans_type = '';
    private $trans_zip = array();
    private $trans_url = '';

    public function __construct() {
        if (!class_exists('ZipArchive')) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p>' . esc_html__('WPfanyi Import requires the PHP ZipArchive extension. Please contact your hosting provider to enable it.', 'wpfanyi-import') . '</p></div>';
            });
            return;
        }

        add_action(is_multisite() ? 'network_admin_menu' : 'admin_menu', function () {
            add_submenu_page(
                is_multisite() ? 'index.php' : 'tools.php',
                __('Import Translation', 'wpfanyi-import'),
                __('Import Translation', 'wpfanyi-import'),
                is_multisite() ? 'manage_network_options' : 'manage_options',
                'wpfanyi_import',
                array($this, 'wpfanyi_import_page')
            );
        });

        add_filter(sprintf('%splugin_action_links_%s', is_multisite() ? 'network_admin_' : '', WPF_BASE_NAME), function ($links) {
            return array_merge(
                [sprintf('<a href="%s">%s</a>', is_multisite() ? network_admin_url('index.php?page=wpfanyi_import') : admin_url('tools.php?page=wpfanyi_import'), __('Import', 'wpfanyi-import'))],
                $links
            );
        });

        add_action('admin_enqueue_scripts', array($this, 'register_css_and_js'));
    }

    public function wpfanyi_import_page() {
        if (isset($_SERVER['REQUEST_METHOD']) && 'POST' === strtoupper($_SERVER['REQUEST_METHOD'])) {
            if (isset($_POST['trans_import_method'])) {
                $this->trans_import_method = sanitize_text_field($_POST['trans_import_method'] ?? '');
                $this->trans_type = sanitize_text_field($_POST['trans_type'] ?? '');
                $this->trans_zip = array(
                    'name'     => sanitize_text_field($_FILES['trans_zip']['name'] ?? ''),
                    'type'     => sanitize_text_field($_FILES['trans_zip']['type'] ?? ''),
                    'tmp_name' => sanitize_text_field($_FILES['trans_zip']['tmp_name'] ?? ''),
                );
                $this->trans_url = isset($_POST['trans_url']) ? esc_url_raw($_POST['trans_url']) : '';

                if ($this->data_verify()) {
                    if ($this->import_trans()) {
                        $this->success_msg(__('Translation imported successfully!', 'wpfanyi-import'));
                    }
                }
            } elseif (isset($_POST['clean_temp_files']) && wp_verify_nonce($_POST['_wpnonce'], 'wpfanyi-clean-temp-nonce')) {
                $this->clean_temp_files();
            }
        }

        require_once __DIR__ . '/page.php';
    }

    public function register_css_and_js($page) {
        if ('tools_page_wpfanyi_import' !== $page && 'index_page_wpfanyi_import' !== $page) {
            return;
        }
        wp_enqueue_script('wpf', WPF_DIR_URL . 'assets/js/script.js', ['jquery'], WPF_VERSION, true);
        wp_enqueue_style('wpf-style', WPF_DIR_URL . 'assets/css/style.css', [], WPF_VERSION);
    }

    public function render_radio_options() {
        $options = [
            'plugin' => __('Plugin', 'wpfanyi-import'),
            'theme'  => __('Theme', 'wpfanyi-import'),
            'auto'   => __('Auto', 'wpfanyi-import'),
        ];
        foreach ($options as $value => $label) {
            $checked = $value === 'plugin' ? 'checked' : '';
            echo "<label><input type='radio' name='trans_type' value='$value' $checked> $label</label> ";
        }
        echo '<p class="description">' . esc_html__('Select the type of translation package. See the "Package Formats" tab for details.', 'wpfanyi-import') . '</p>';
    }

    private function clean_temp_files() {
        WP_Filesystem();
        global $wp_filesystem;

        $languages_dir = WP_CONTENT_DIR . '/languages/';
        if (!$wp_filesystem->is_dir($languages_dir) || !$wp_filesystem->is_writable($languages_dir)) {
            $this->error_msg(__('The languages directory is not writable or does not exist.', 'wpfanyi-import'));
            return;
        }

        $deleted_count = $this->delete_temp_json_files($languages_dir);
        
        if ($deleted_count > 0) {
            $this->success_msg(sprintf(__('Successfully deleted %d temporary JSON files.', 'wpfanyi-import'), $deleted_count));
        } else {
            $this->error_msg(__('No temporary JSON files found to delete.', 'wpfanyi-import'));
        }
    }

    private function delete_temp_json_files($dir) {
        global $wp_filesystem;
        $deleted_count = 0;

        $files = $wp_filesystem->dirlist($dir);
        if (!$files) {
            return $deleted_count;
        }

        foreach ($files as $file) {
            $filename = $file['name'];
            $full_path = rtrim($dir, '/') . '/' . $filename;

            if ($wp_filesystem->is_dir($full_path)) {
                // Recursively clean subdirectories
                $deleted_count += $this->delete_temp_json_files($full_path);
            } elseif ($wp_filesystem->is_file($full_path)) {
                // Match temporary JSON files (e.g., zh_CN-*.json or plugin/theme-zh_CN-*.json)
                if (preg_match('/\.(json)$/i', $filename) && preg_match('/-[a-f0-9]{32}\.json$/', $filename)) {
                    if ($wp_filesystem->delete($full_path)) {
                        $deleted_count++;
                    }
                }
            }
        }

        return $deleted_count;
    }

    private function data_verify() {
        if (!current_user_can('install_plugins') || !(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'wpfanyi-import-nonce'))) {
            $this->error_msg(__('You don\'t have the authority to do that', 'wpfanyi-import'));
            return false;
        }

        if (!in_array($this->trans_type, ['plugin', 'theme', 'auto'], true)) {
            $this->error_msg(__('Unexpected translation package type', 'wpfanyi-import'));
            return false;
        }

        if ('file' === $this->trans_import_method) {
            if (empty($this->trans_zip['name'])) {
                $this->error_msg(__('Translation package not selected', 'wpfanyi-import'));
                return false;
            }
            if (!in_array($this->trans_zip['type'], ['application/x-zip-compressed', 'application/zip'], true)) {
                $this->error_msg(__('The translation package should be in ZIP format', 'wpfanyi-import'));
                return false;
            }
        } elseif ('url' === $this->trans_import_method) {
            if (!preg_match("#(http|https)://(.*\.)?.*\..*#i", $this->trans_url)) {
                $this->error_msg(__('Invalid URL format', 'wpfanyi-import'));
                return false;
            }
        } else {
            $this->error_msg(__('Parameter error: unknown translation package import method', 'wpfanyi-import'));
            return false;
        }

        return true;
    }

    private function import_trans() {
        $trans_zip_file = 'file' === $this->trans_import_method ? $this->trans_zip['tmp_name'] : download_url($this->trans_url, 1000);
        if (!file_exists($trans_zip_file) || filesize($trans_zip_file) <= 0) {
            $this->error_msg('file' === $this->trans_import_method ?
                __('Translation package upload failed, please check filesystem permissions', 'wpfanyi-import') :
                __('Translation package acquisition failed, please check the URL', 'wpfanyi-import'));
            return false;
        }

        $trans_tmp_dir = $this->unzip($trans_zip_file);
        if (!$trans_tmp_dir) {
            return false;
        }

        $trans_types = 'auto' === $this->trans_type ? $this->get_trans_type($trans_tmp_dir) : [$this->trans_type => ''];
        if (is_wp_error($trans_types)) {
            $this->error_msg($trans_types->get_error_message());
            return false;
        }

        foreach ($trans_types as $trans_type => $trans_dir) {
            $trans_files = [];
            foreach (scandir("{$trans_tmp_dir}{$trans_dir}") as $filename) {
                if (!in_array($filename, ['.', '..'])) {
                    $trans_files[] = "{$trans_tmp_dir}{$trans_dir}/{$filename}";
                }
            }

            $trans_files = $this->prepare_trans_file($trans_files);
            if (is_wp_error($trans_files)) {
                $this->error_msg($trans_files->get_error_message());
                return false;
            }

            $res = $this->save_to_trans_dir($trans_files, $trans_type);
            if (is_wp_error($res)) {
                $this->error_msg($res->get_error_message());
                return false;
            }
        }

        @unlink($trans_zip_file);
        return true;
    }

    private function get_trans_type($dir) {
        $data = [];
        foreach (scandir($dir) as $item) {
            if (in_array($item, ['plugin', 'plugins'])) {
                $data['plugin'] = "/{$item}";
            } elseif (in_array($item, ['theme', 'themes'])) {
                $data['theme'] = "/{$item}";
            }
        }

        return empty($data) ? new WP_Error('wpf_type_identification_failed', __('No translation package was successfully identified.', 'wpfanyi-import')) : $data;
    }

    private function unzip($zip_filename) {
        WP_Filesystem();
        global $wp_filesystem;

        $tmp_dir = $wp_filesystem->wp_content_dir() . '/upgrade/wpfanyi_' . md5(rand());
        if (!$wp_filesystem->mkdir($tmp_dir)) {
            $this->error_msg(__('Unable to create temporary directory.', 'wpfanyi-import'));
            return false;
        }

        $zip = new ZipArchive;
        if ($zip->open($zip_filename) !== true || !$zip->extractTo($tmp_dir)) {
            $this->error_msg(__('Failed to extract ZIP file.', 'wpfanyi-import'));
            $wp_filesystem->rmdir($tmp_dir, true);
            return false;
        }
        $zip->close();
        return $tmp_dir;
    }

    private function save_to_trans_dir($files, $trans_type) {
        WP_Filesystem();
        global $wp_filesystem;

        $wp_trans_store_dir = WP_CONTENT_DIR . "/languages/{$trans_type}s/";
        if (!$wp_filesystem->is_writable($wp_trans_store_dir)) {
            if ($wp_filesystem->exists($wp_trans_store_dir)) {
                return new WP_Error('wpf_file_permission_error', sprintf(__('The translation storage directory is not writable: %s', 'wpfanyi-import'), $wp_trans_store_dir));
            } elseif (!$wp_filesystem->mkdir($wp_trans_store_dir, 0775, true)) {
                return new WP_Error('wpf_file_permission_error', __('Failed to create translation storage directory.', 'wpfanyi-import'));
            }
        }

        foreach ($files as $file) {
            $wp_filesystem->copy($file, $wp_trans_store_dir . basename($file), true);
        }
        return true;
    }

    private function prepare_trans_file($files) {
        $trans_files = [];
        $exist_mo = false;

        foreach ($files as $file) {
            if (preg_match("#.*\.(mo|po)#i", $file) && file_exists($file) && filesize($file) > 0) {
                $trans_files[] = $file;
                if (preg_match("#.*\.mo#i", $file)) {
                    $exist_mo = true;
                }
            }
        }

        return !$exist_mo ? new WP_Error('wpf_trans_not_found', __('No valid translation was found.', 'wpfanyi-import')) : $trans_files;
    }

    private function success_msg($msg) {
        add_settings_error('wpfanyi_import_messages', 'wpfanyi_import_success', $msg, 'success');
    }

    private function error_msg($msg) {
        add_settings_error('wpfanyi_import_messages', 'wpfanyi_import_error', $msg, 'error');
    }
}