<?php

/**
 * Plugin Name: Trufi Route Pages
 * Plugin URI: https://trufi.app/
 * Description: A plugin for displaying Trufi Maps.
 * Version: 0.7.0
 * Author: Trufi Association
 * Author URI: https://trufi.app/
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: trufi-maps
 */

if( ! function_exists('get_plugin_data') ){
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
$plugin_data = get_plugin_data( __FILE__, false );
define( 'TRUFI_ROUTES_PLUGIN_VERSION', ($plugin_data && $plugin_data['Version']) ? $plugin_data['Version'] : '1.0.0' );

require_once('scripts/constants.php');
require_once('scripts/functions.php');
require_once('scripts/admin-settings.php');
require_once('scripts/enqueue-scripts.php');
require_once('scripts/rewrite-rules.php');
require_once('scripts/template-redirect.php');
require_once('scripts/sitemap-provider.php');
