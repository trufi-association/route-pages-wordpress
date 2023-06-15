<?php

function trufi_maps_template_redirect()
{
  if (get_query_var('trufi_map_id') && get_query_var('trufi_map_name')) {
    $map_id = get_query_var('trufi_map_id');
    $map_name = get_query_var('trufi_map_name');
    $api_url = get_option(TRUFI_API_URL_OPTION);
    $page_title = get_bloginfo('name');
    $page_description = get_option(TRUFI_SITE_DESCRIPTION_OPTION);
    $line_color = get_option(TRUFI_LINE_COLOR_OPTION);
    $line_weight = get_option(TRUFI_LINE_WEIGHT_OPTION);
    $google_play_url = get_option(TRUFI_GOOGLE_PLAY_URL_OPTION);
    $apple_store_url = get_option(TRUFI_APPLE_STORE_URL_OPTION);
    $google_play_image = get_option(TRUFI_GOOGLE_PLAY_IMAGE_OPTION);
    $apple_store_image = get_option(TRUFI_APPLE_STORE_IMAGE_OPTION);

    add_action('wp_head', function () use ($page_title, $page_description) {
      trufi_add_header_tags($page_title, $page_description);
    });

    $replacement_values = array(
      "{{mapId}}" => $map_id,
      "{{apiUrl}}" => $api_url,
      "{{pageTitle}}" => $page_title,
      "{{lineColor}}" => $line_color,
      "{{lineWeight}}" => $line_weight,
      "{{callToAction}}" => $page_description,
      "{{googlePlayUrl}}" => $google_play_url,
      "{{appleStoreUrl}}" => $apple_store_url,
      "{{googlePlayImage}}" => $google_play_image,
      "{{appleStoreImage}}" => $apple_store_image,
    );

    $template_path = plugin_dir_path(__FILE__) . '../templates/map-template.html';
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
