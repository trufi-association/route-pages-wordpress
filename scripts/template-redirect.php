<?php

use Api\TrufiApi;

add_action('template_redirect', 'trufi_maps_template_redirect');
function trufi_maps_template_redirect() {
    if (get_query_var('trufi_map_id') && get_query_var('trufi_map_name')) {
        $map_id   = get_query_var('trufi_map_id');
        $map_name = get_query_var('trufi_map_name');
        $api_url  = get_option(TRUFI_API_URL_OPTION);

        $trufiApi  = new TrufiApi($api_url);
        $routeData = $trufiApi->fetchRoute($map_id);

        $page_title        = $routeData['data']['pattern']['route']['longName'] . ' - ' . get_bloginfo('name');
        $page_description  = get_option(TRUFI_SITE_DESCRIPTION_OPTION);
        $line_color        = get_option(TRUFI_LINE_COLOR_OPTION);
        $line_weight       = get_option(TRUFI_LINE_WEIGHT_OPTION);
        $google_play_url   = get_option(TRUFI_GOOGLE_PLAY_URL_OPTION);
        $apple_store_url   = get_option(TRUFI_APPLE_STORE_URL_OPTION);
        $google_play_image = get_option(TRUFI_GOOGLE_PLAY_IMAGE_OPTION);
        $apple_store_image = get_option(TRUFI_APPLE_STORE_IMAGE_OPTION);
        $map_page_id       = get_option(TRUFI_MAP_PAGE_ID_OPTION);

        add_action('wp_head', function () use ($page_title, $page_description) {
            trufi_add_header_tags($page_title, $page_description);
        });

        $replacement_values = [
            "{{mapId}}"           => $map_id,
            "{{apiUrl}}"          => $api_url,
            "{{pageTitle}}"       => $page_title,
            "{{lineColor}}"       => $line_color,
            "{{lineWeight}}"      => $line_weight,
            "{{callToAction}}"    => $page_description,
            "{{googlePlayUrl}}"   => $google_play_url,
            "{{appleStoreUrl}}"   => $apple_store_url,
            "{{googlePlayImage}}" => $google_play_image,
            "{{appleStoreImage}}" => $apple_store_image,
            "{{routeData}}"       => json_encode($routeData),
        ];

        $template_path    = plugin_dir_path(__FILE__) . '../templates/map-template.html';
        $template_content = file_get_contents($template_path);
        $template_content = str_replace(array_keys($replacement_values), array_values($replacement_values), $template_content);

        global $post;

        $post->ID             = $map_page_id;
        $post->post_title     = $page_title;
        $post->post_name      = $map_name;
        $post->post_content   = minify_html($template_content);
        $post->comment_status = 'closed';
        $post->post_type      = 'page';
        $post->post_status    = 'publish';
        $post->post_parent    = $map_page_id;
        $post->menu_order     = 0;
        $post->comment_count  = 0;


        add_action('the_title', function () use ($page_title) {
            $title = $page_title;
            return $title;
        });

        global $wp_query;
        $wp_query->is_singular = true;
        $wp_query->is_page     = true;
        $wp_query->is_home     = false;
    }
}

/*add_filter('template_include', function ($template) {
    global $post;
    if ($post->ID === 0) {

        $map_page_template = get_option(TRUFI_MAP_PAGE_TEMPLATE_OPTION);
        $template_path     = false;
        if ($map_page_template) {
            $template_path = get_template_directory() . '/' . $map_page_template;
        }

        if (file_exists($template_path) && !wp_is_block_theme()) {
            return $template_path;
        } else {
            $functions = ['get_page_template', 'get_single_template', 'get_singular_template', 'get_index_template'];
            foreach ($functions as $function) {
                $_template = $function();

                if ($_template) {
                    return $_template;
                }
            }
        }
    }

    return $template;
});*/
