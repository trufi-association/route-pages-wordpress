<?php

/**
 * Plugin Name: Trufi Route Pages
 * Plugin URI: https://trufi.app/
 * Description: A plugin for displaying Trufi Maps.
 * Version: 1.0
 * Author: Trufi Association
 * Author URI: https://trufi.app/
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: trufi-maps
 */

define('TRUFI_API_URL_OPTION', 'trufi_api_url');
define('TRUFI_SITE_TITLE_OPTION', 'trufi_site_title');
define('TRUFI_SITE_DESCRIPTION_OPTION', 'trufi_site_description');
define('TRUFI_LINE_COLOR_OPTION', 'trufi_line_color');
define('TRUFI_LINE_WEIGHT_OPTION', 'trufi_line_weight');

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

?>
  <div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form method="post" action="">
      <?php wp_nonce_field('trufi_api_options_nonce', 'trufi_api_options_nonce'); ?>
      <table class="form-table">
        <tr>
          <th scope="row">
            <label for="trufi_site_title"><?php _e('Trufi site title', 'trufi-maps'); ?></label>
          </th>
          <td>
            <input type="text" name="trufi_site_title" id="trufi_site_title" value="<?php echo esc_attr($site_title); ?>" class="regular-text">
            <p class="description"><?php _e('Enter the title of the trufi site.', 'trufi-maps'); ?></p>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="trufi_site_description"><?php _e('Site description', 'trufi-maps'); ?></label>
          </th>
          <td>
            <input type="text" name="trufi_site_description" id="trufi_site_description" value="<?php echo esc_attr($site_description); ?>" class="large-text">
            <p class="description"><?php _e('Enter the description of the trufi site.', 'trufi-maps'); ?></p>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="trufi_api_url"><?php _e('API url', 'trufi-maps'); ?></label>
          </th>
          <td>
            <input type="text" name="trufi_api_url" id="trufi_api_url" value="<?php echo esc_attr($api_url); ?>" class="large-text">
            <p class="description"><?php _e('Enter the URL of the Trufi Graphql API.', 'trufi-maps'); ?></p>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="trufi_line_color"><?php _e('Line color', 'trufi-maps'); ?></label>
          </th>
          <td>
            <input type="text" name="trufi_line_color" id="trufi_line_color" value="<?php echo esc_attr($line_color); ?>" class="line-color-field">
            <p class="description"><?php _e('Choose a color for the track line.', 'trufi-maps'); ?></p>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="trufi_line_weight"><?php _e('Line Weight', 'trufi-maps'); ?></label>
          </th>
          <td>
            <input type="number" name="trufi_line_weight" id="trufi_line_weight" value="<?php echo esc_attr($line_weight); ?>" class="small-text" min="1" max="10">
            <p class="description"><?php _e('Enter the line weight (1-10).', 'trufi-maps'); ?></p>
          </td>
        </tr>

      </table>
      <?php submit_button(__('Save Changes', 'trufi-maps')); ?>
    </form>
  </div>
  <script>
    jQuery(document).ready(function($) {
      $('.line-color-field').wpColorPicker();
    });
  </script>
<?php
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

function trufi_enqueue_color_picker($hook_suffix)
{
  if ($hook_suffix == 'settings_page_trufi-api-settings') {
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('trufi-script-handle', plugins_url('trufi-script.js', __FILE__), array('wp-color-picker'), false, true);
  }
}
add_action('admin_enqueue_scripts', 'trufi_enqueue_color_picker');

function trufi_maps_add_rewrite_rules()
{
  add_rewrite_tag('%trufi_map_id%', '([^/]+)');
  add_rewrite_tag('%trufi_map_name%', '([^/]+)');
  add_rewrite_rule('^routes/([^/]+)/([^/]+)/?', 'index.php?trufi_map_name=$matches[1]&trufi_map_id=$matches[2]', 'top');
  add_rewrite_endpoint('routes', EP_PERMALINK | EP_PAGES);
}
add_action('init', 'trufi_maps_add_rewrite_rules');

function trufi_maps_template_redirect()
{
  if (get_query_var('trufi_map_id') && get_query_var('trufi_map_name')) {
    $map_id = get_query_var('trufi_map_id');
    $map_name = get_query_var('trufi_map_name');
    $api_url = get_option(TRUFI_API_URL_OPTION);
    $page_title = get_option(TRUFI_SITE_TITLE_OPTION);
    $page_description = get_option(TRUFI_SITE_DESCRIPTION_OPTION);
    $line_color = get_option(TRUFI_LINE_COLOR_OPTION);
    $line_weight = get_option(TRUFI_LINE_WEIGHT_OPTION);

    add_action('wp_head', function () use ($page_title, $page_description) {
      trufi_add_header_tags($page_title, $page_description);
    });

    $replacement_values = array(
      "{{mapId}}" => $map_id,
      "{{apiUrl}}" => $api_url,
      "{{lineColor}}" => $line_color,
      "{{lineWeight}}" => $line_weight,
    );

    $template_path = plugin_dir_path(__FILE__) . 'map-template.html';
    $template_content = file_get_contents($template_path);
    $template_content = str_replace(array_keys($replacement_values), array_values($replacement_values), $template_content);

    global $post;

    $post->ID             = 0;
    $post->post_title     = $page_title;
    $post->post_name      = $map_name;
    $post->post_content   = minify_html($template_content);
    $post->comment_status = 'closed';
    $post->post_type      = 'page';
    $post->post_status    = 'publish';
    $post->page_template  = 'default';
    $post->post_parent    = 0;
    $post->menu_order     = 0;
    $post->comment_count  = 0;

    $functions = ['get_page_template', 'get_single_template', 'get_singular_template', 'get_index_template'];

    foreach ($functions as $function) {
      $template = $function();

      if ($template) {
        break;
      }
    }

    global $wp_query;
    $wp_query->is_singular = true;

    $template = apply_filters('template_include', $template);
    include $template;

    exit;
  }
}
add_action('template_redirect', 'trufi_maps_template_redirect');

function trufi_add_header_tags($page_title, $page_description)
{
  echo '<meta name="description" content="' . esc_attr($page_description) . '">';
  echo '<meta property="og:title" content="' . esc_attr($page_title) . '">';
  echo '<meta property="og:description" content="' . esc_attr($page_description) . '">';
  echo '<meta property="og:type" content="website">';
  echo '<meta property="og:url" content="' . esc_url(home_url($_SERVER['REQUEST_URI'])) . '">';
  echo '<meta name="twitter:card" content="summary_large_image">';
  echo '<meta name="twitter:title" content="' . esc_attr($page_title) . '">';
  echo '<meta name="twitter:description" content="' . esc_attr($page_description) . '">';
  echo '<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />';
  echo '<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>';
}

function minify_html($input)
{
  return preg_replace(array('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '/\s*(<[^>]+>)\s*/', '/\s\s+/', '/\n/',), array('', '$1', ' ', '',), $input);
}
