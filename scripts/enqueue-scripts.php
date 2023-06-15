<?php

function trufi_enqueue_admin_scripts($hook_suffix)
{
  if ($hook_suffix == 'settings_page_trufi-api-settings') {
    wp_enqueue_media();
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('trufi-script-handle', plugins_url('../js/admin-settings-script.js', __FILE__), array('wp-color-picker', 'jquery', 'media-upload'), false, true);
  }
}
add_action('admin_enqueue_scripts', 'trufi_enqueue_admin_scripts');
