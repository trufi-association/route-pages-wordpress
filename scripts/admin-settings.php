<?php

function trufi_api_options_page()
{
  if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
  }

  $api_url = get_option(TRUFI_API_URL_OPTION);
  $site_title = get_option(TRUFI_SITE_TITLE_OPTION);
  $site_description = get_option(TRUFI_SITE_DESCRIPTION_OPTION);
  $line_color = get_option(TRUFI_LINE_COLOR_OPTION);
  if (!$line_color) $line_color = "#ff0000";
  $line_weight = get_option(TRUFI_LINE_WEIGHT_OPTION);
  if (!$line_weight) $line_weight = '7';

  if (isset($_POST['trufi_api_options_nonce'])) {
    if (!wp_verify_nonce($_POST['trufi_api_options_nonce'], 'trufi_api_options_nonce')) {
      wp_die('Invalid nonce.');
    }

    $api_url = esc_url($_POST['trufi_api_url']);
    $site_title = $_POST['trufi_site_title'];
    $site_description = $_POST['trufi_site_description'];
    $line_color = $_POST['trufi_line_color'];
    $line_weight = $_POST['trufi_line_weight'];

    update_option(TRUFI_API_URL_OPTION, $api_url);
    update_option(TRUFI_SITE_TITLE_OPTION, $site_title);
    update_option(TRUFI_SITE_DESCRIPTION_OPTION, $site_description);
    update_option(TRUFI_LINE_COLOR_OPTION, $line_color);
    update_option(TRUFI_LINE_WEIGHT_OPTION, $line_weight);

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
