<?php

function trufi_api_options_page()
{
  if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
  }

  $api_url = get_option(TRUFI_API_URL_OPTION);
  $site_description = get_option(TRUFI_SITE_DESCRIPTION_OPTION);
  $line_color = get_option(TRUFI_LINE_COLOR_OPTION);
  if (!$line_color) $line_color = "#ff0000";
  $line_weight = get_option(TRUFI_LINE_WEIGHT_OPTION);
  if (!$line_weight) $line_weight = '7';
  $google_play_url = get_option(TRUFI_GOOGLE_PLAY_URL_OPTION);
  $apple_store_url = get_option(TRUFI_APPLE_STORE_URL_OPTION);
  $google_play_image = get_option(TRUFI_GOOGLE_PLAY_IMAGE_OPTION);
  $apple_store_image = get_option(TRUFI_APPLE_STORE_IMAGE_OPTION);
  $cache_ttl = get_option(TRUFI_CACHE_TTL_OPTION);
  if (!$cache_ttl) $cache_ttl = 24;


  if (isset($_POST['trufi_api_options_nonce'])) {
    if (!wp_verify_nonce($_POST['trufi_api_options_nonce'], 'trufi_api_options_nonce')) {
      wp_die('Invalid nonce.');
    }

    $api_url = esc_url($_POST['trufi_api_url']);
    $site_description = $_POST['trufi_site_description'];
    $line_color = $_POST['trufi_line_color'];
    $line_weight = $_POST['trufi_line_weight'];
    $google_play_url = $_POST['trufi_google_play_url'];
    $apple_store_url = $_POST['trufi_apple_store_url'];
    $google_play_image = $_POST['trufi_google_play_image'];
    $apple_store_image = $_POST['trufi_apple_store_image'];
    $cache_ttl = $_POST['trufi_cache_ttl'];

    update_option(TRUFI_API_URL_OPTION, $api_url);
    update_option(TRUFI_SITE_DESCRIPTION_OPTION, $site_description);
    update_option(TRUFI_LINE_COLOR_OPTION, $line_color);
    update_option(TRUFI_LINE_WEIGHT_OPTION, $line_weight);
    update_option(TRUFI_GOOGLE_PLAY_URL_OPTION, $google_play_url);
    update_option(TRUFI_APPLE_STORE_URL_OPTION, $apple_store_url);
    update_option(TRUFI_GOOGLE_PLAY_IMAGE_OPTION, $google_play_image);
    update_option(TRUFI_APPLE_STORE_IMAGE_OPTION, $apple_store_image);
    update_option(TRUFI_CACHE_TTL_OPTION, $cache_ttl);

    echo '<div class="notice notice-success"><p>' . __('Configuration saved.', 'trufi-maps') . '</p></div>';
  }

  include(plugin_dir_path(__FILE__) . '../templates/admin-settings-form.php');
}

function trufi_api_add_options_page()
{
  add_options_page(
    __('Trufi Route Pages Settings', 'trufi-routes'),
    __('Trufi Route Pages', 'trufi-routes'),
    'manage_options',
    'trufi-api-settings',
    'trufi_api_options_page'
  );
}
add_action('admin_menu', 'trufi_api_add_options_page');
