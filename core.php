<?php
/**
 * Class WPfanyi_Import
 *
 * Core class
 *
 * @package wpfanyi-import
 */
class WPfanyi_Import {

    /**
     * @var string Translation package import method. value:"file" or "url"
     *
     * @since 1.0.0
     */
    private $trans_import_method = '';

    /**
     * @var string Translation package type. value:"plugin" or "theme"
     *
     * @since 1.0.0
     */
    private $trans_type = '';

    /**
     * @var array Translation package information uploaded by users
     *
     * @since 1.0.0
     */
    private $trans_zip = [];

    /**
     * @var string Translation package URL
     *
     * @since 1.0.0
     */
    private $trans_url = '';

    public function __construct() {
        /**
         * Register menu
         */
        add_action(is_multisite() ? 'network_admin_menu' : 'admin_menu', function () {
            add_submenu_page(
                is_multisite() ? 'index.php' : 'tools.php',
                __('import translation', 'wpfanyi-import'),
                __('import translation', 'wpfanyi-import'),
                is_multisite() ? 'manage_network_options' : 'manage_options',
                'wpfanyi_import',
                [$this, 'wpfanyi_import_page']
            );
        });

        add_action('admin_enqueue_scripts', [$this, 'register_css_and_js']);
    }

    /**
     * Output and process translation import form
     *
     * @since 1.0.0
     */
    public function wpfanyi_import_page() {
        /** If it is a post request, the form value is processed */
        if (isset($_SERVER['REQUEST_METHOD']) && 'POST' === strtoupper($_SERVER['REQUEST_METHOD'])) {
            $this->trans_import_method = @$_POST['trans_import_method'];
            $this->trans_type = @$_POST['trans_type'];
            $this->trans_zip = @$_FILES['trans_zip'];
            $this->trans_url = @$_POST['trans_url'];

            if ($this->data_verify()) {
                if ($this->import_trans()) {
                    $this->success_msg(__('Translation imported successfully!', 'wpfanyi-import'));
                }
            }
        }

        require_once 'page.php';
    }

    /**
     * Register CSS and JS for forms
     *
     * @todo JS dependency order problem
     *
     * @param $page string Current page
     */
    public function register_css_and_js($page) {
        if ('tools_page_wpfanyi_import' !== $page) {
            return;
        }

        wp_enqueue_script('layui', WPF_DIR_URL . 'assets/js/layui.min.js', [], WPF_VERSION);
        wp_enqueue_script('sisyphus', WPF_DIR_URL . 'assets/js/sisyphus.min.js', ['layui'], WPF_VERSION);
        wp_enqueue_script('wpf', WPF_DIR_URL . 'assets/js/wpf.js', ['jquery', 'layui', 'sisyphus'], WPF_VERSION);
        wp_enqueue_style('layui', WPF_DIR_URL . 'assets/css/layui.min.css', [], WPF_VERSION);
        wp_enqueue_style('wpf-style', WPF_DIR_URL . 'assets/css/wpf-style.css', ['layui'], WPF_VERSION);
    }

    /**
     * Verify the data submitted by the user
     *
     * @since 1.0.0
     *
     * @return bool true on success or false on failure.
     */
    private function data_verify() {
        if (!current_user_can( 'install_plugins' ) || !(isset($_POST['_wpnonce']) &&
            wp_verify_nonce($_POST['_wpnonce'], 'wpfanyi-import-nonce'))) {
            $this->error_msg(__('You don\'t have the authority to do that', 'wpfanyi-import'));

            return false;
        }

        if ('plugin' !== $this->trans_type && 'theme' !== $this->trans_type) {
            $this->error_msg(__('Unexpected translation package type', 'wpfanyi-import'));

            return false;
        }

        if ('file' === $this->trans_import_method) {
            if (empty($this->trans_zip['name'])) {
                $this->error_msg(__('Translation package not selected', 'wpfanyi-import'));

                return false;
            }
            if ('application/x-zip-compressed' !== @$this->trans_zip['type']) {
                $this->error_msg(__('The translation package should be in ZIP format', 'wpfanyi-import'));

                return false;
            }
        } elseif ('url' === $this->trans_import_method) {
            $pattern="#(http|https)://(.*\.)?.*\..*#i";
            if (!preg_match($pattern, $this->trans_url)) {
                $this->error_msg(__('Invalid URL format', 'wpfanyi-import'));

                return false;
            }
        } else {
            $this->error_msg(__('Parameter error: unknown translation package import method', 'wpfanyi-import'));

            return false;
        }


        return true;
    }

    /**
     * Handling translation package import
     *
     * @since 1.0.0
     *
     * @return bool true on success or false on failure.
     */
    private function import_trans() {
        $trans_dir = WP_CONTENT_DIR . '/languages/' . $this->trans_type . 's';

        $trans_zip_file = 'file' === $this->trans_import_method ? @$this->trans_zip['tmp_name'] : download_url($this->trans_url, $timeout = 1000);

        if(!file_exists($trans_zip_file) && filesize($trans_zip_file) > 0) {
            if ('file' === $this->trans_import_method) {
                $this->error_msg(__('Translation package upload failed, please check whether the file system permissions are normal', 'wpfanyi-import'));
            } else {
                $this->error_msg(__('Translation package acquisition failed, please check whether the URL is valid', 'wpfanyi-import'));
            }

            return false;
        }

        if(!is_writable($trans_dir)) {
            /**
             * When it is found that the translation directory isn't writable, try to create a new directory.
             * If it fails, it proves that there is a problem with the file system permissions.
             */
            if (!mkdir($trans_dir, 0775, true)) {
                /* translators: %s: Translation storage directory */
                $this->error_msg(sprintf(__('The translation storage directory of this WordPress is not writable：%s', 'wpfanyi-import'), $trans_dir));

                return false;
            }
        }

        if (!class_exists('ZipArchive')) {
            $this->error_msg(__('This server doesn‘t support the decompression of Zip archives. Please contact the service provider of this server to enable the zip extension module function of PHP.', 'wpfanyi-import'));

            return false;
        }

        $zip = new ZipArchive;
        $res = $zip->open($trans_zip_file);
        if (!$res) {
            $this->error_msg(__('Failed to parse the Zip package. The Zip package may be damaged', 'wpfanyi-import'));
        }

        /**
         * @var array Save the file name of the Mo and Po files read from the zip package
         *
         * @since 1.0.0
         */
        $trans_file_list = [];

        /** Read valid Mo and Po files from the translation package to prevent code injection */
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            $pattern="#.*\.(mo|po)#i";
            if (preg_match($pattern, $filename)) {
                $trans_file_list[] = $filename;
            }
        }

        if (empty($trans_file_list)) {
            $this->error_msg(__('There are no valid Po and Mo files in the current translation package', 'wpfanyi-import'));

            return false;
        }

        $zip->extractTo($trans_dir, $trans_file_list);
        $zip->close();

        /** Try to delete the temporary file after all operations */
        @unlink($trans_zip_file);

        return true;
    }

    /**
     * Print success message
     *
     * @since 1.0.0
     *
     * @param string $msg Message
     */
    private function success_msg($msg) {
        echo "<div id='message' class='updated notice'><p>{$msg}</p></div>";
    }

    /**
     * Print fail message
     *
     * @since 1.0.0
     *
     * @param string $msg Message
     */
    private function error_msg($msg) {
        echo "<div id='message' class='updated error'><p>{$msg}</p></div>";
    }

}
