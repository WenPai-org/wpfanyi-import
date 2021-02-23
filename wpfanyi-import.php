<?php
/**
 * Plugin Name: WPfanyi import
 * Description: Upload and install WordPress language packs just like installing WordPress theme plugins. Don't need to use FTP or SFTP software to upload.This time-saving and lightweight tool will help you import any plugins and theme's translated language packs in standard formats Zip quickly and easily.
 * Author: WenPai.org
 * Author URI:https://wenpai.org/
 * Text Domain: wpfanyi-import
 * Domain Path: /languages
 * Version: 1.0.0
 * Network: True
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * WPfanyi import is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * WPfanyi import is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

defined( 'ABSPATH' ) || exit;

add_action('init', function () {
    if (is_admin() && current_user_can( 'install_plugins' )) {
        define('WPF_VERSION', '1.0.0');

        /** Load core */
        require_once 'core.php';
        new WPfanyi_Import();
    }
});
