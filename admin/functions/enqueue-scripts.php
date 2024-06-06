<?php

function trufi_enqueue_admin_scripts($hook_suffix) {
    if ($hook_suffix == 'toplevel_page_trufi-routes-settings') {
        global $plugin_url;
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('TrufiApi-script-handle', $plugin_url . '/assets/js/admin-settings-script.js', ['wp-color-picker', 'jquery', 'media-upload'],
            false, true);
    }
}

add_action('admin_enqueue_scripts', 'trufi_enqueue_admin_scripts');
