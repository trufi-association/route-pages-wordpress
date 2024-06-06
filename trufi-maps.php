<?php

/**
 * Plugin Name: Trufi Route Pages
 * Plugin URI: https://trufi.app/
 * Description: A plugin for displaying Trufi Maps.
 * Version: 0.8.1
 * Author: Trufi Association
 * Author URI: https://trufi.app/
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: TrufiApi-maps
 */

if (!function_exists('get_plugin_data')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}
$plugin_data = get_plugin_data(__FILE__, false);
define('TRUFI_ROUTES_PLUGIN_VERSION', ($plugin_data && $plugin_data['Version']) ? $plugin_data['Version'] : '1.0.0');

// check php version if less than 8.0 don't load
if (version_compare(phpversion(), '8.0', '<')) {
    add_action('admin_notices', 'trufi_php_version_notice');
    function trufi_php_version_notice()
    {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e('Trufi Route Pages plugin requires PHP 8.0 or higher. Please update your PHP version.', 'TrufiApi-maps'); ?></p>
        </div>
        <?php
    }
    return;
}

require('functions/constants.php');
require('api/TrufiApi.php');
require('functions/functions.php');
require('functions/enqueue-scripts.php');
require('functions/rewrite-rules.php');
require('functions/template-redirect.php');
require('functions/sitemap-provider.php');

if (is_admin()) {
    require('admin/transient-cache-clear.php');
    require('admin/admin-settings.php');
}
