<?php
/**
 * Plugin Name: WPfanyi Import
 * Description: Install translation package like a theme/plugin, no need for FTP/SFTP. This tool will save you a lot of time.
 * Author: WPfanyi.com
 * Author URI: https://wpfanyi.com/
 * Text Domain: wpfanyi-import
 * Domain Path: /languages
 * Version: 1.2.0
 * Network: True
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('ABSPATH') || exit;

add_action('init', function () {
    if (is_admin() && current_user_can('install_plugins')) {
        define('WPF_VERSION', '1.2.0');
        define('WPF_DIR_PATH', plugin_dir_path(__FILE__));
        define('WPF_DIR_URL', plugin_dir_url(__FILE__));
        define('WPF_BASE_NAME', plugin_basename(__FILE__));

        // Load translation
        add_action('init', 'wpfanyi_load_textdomain');
        function wpfanyi_load_textdomain() {
            load_plugin_textdomain('wpfanyi-import', false, dirname(plugin_basename(__FILE__)) . '/languages');
        }

        // Load core
        require_once 'includes/core.php';
        new WPfanyi_Import();
    }
});