<?php

function trufi_enqueue_color_picker($hook_suffix)
{
  if ($hook_suffix == 'settings_page_trufi-api-settings') {
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('trufi-script-handle', plugins_url('trufi-script.js', __FILE__), array('wp-color-picker'), false, true);
  }
}
add_action('admin_enqueue_scripts', 'trufi_enqueue_color_picker');
