<?php

use Api\TrufiApi;

add_action('template_redirect', 'trufi_maps_template_redirect');
function trufi_maps_template_redirect() {
    if (get_query_var('trufi_map_id') && get_query_var('trufi_map_name')) {
        $mapId         = get_query_var('trufi_map_id');
        $mapName       = get_query_var('trufi_map_name');
        $apiUrl        = get_option(TRUFI_API_URL_OPTION);
        $cacheKey      = 'trufi_route_data_' . $mapId;
        $cacheLifetime = get_option(TRUFI_CACHE_TTL_OPTION) * 60 * 60;

        // Check if the route data is cached
        $routeData = get_transient($cacheKey);
        if (false === $routeData) {
            // Not cached, fetch route data from Trufi API
            $trufiApi  = new TrufiApi($apiUrl);
            $routeData = $trufiApi->fetchRoute($mapId);
            set_transient($cacheKey, $routeData, $cacheLifetime);
        }

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
            "{{mapId}}"           => $mapId,
            "{{apiUrl}}"          => $apiUrl,
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
        $post->post_name      = $mapName;
        $post->post_content   = minify_html($template_content);
        $post->comment_status = 'closed';
        $post->post_type      = 'page';
        $post->post_status    = 'publish';
        $post->post_parent    = $map_page_id;
        $post->menu_order     = 0;
        $post->comment_count  = 0;

        /*add_filter('the_content', function ($content) use ($template_content) {
            return $template_content;
        });*/


        function set_route_title($title, $id = null) {
            // if we are on the map page, set the title to the route name, previously set in post_title
            if ($id == get_option(TRUFI_MAP_PAGE_ID_OPTION)) {
                global $post;
                return $post->post_title;
            }
            return $title;
        }

        add_filter('the_title', 'set_route_title', 10, 2);

        function trufi_remove_title_filter_nav_menu($nav_menu, $args) {
            // we are working with menu, so remove the title filter
            remove_filter('the_title', 'set_route_title', 10, 2);
            return $nav_menu;
        }

        // this filter fires just before the nav menu item creation process
        add_filter('pre_wp_nav_menu', 'trufi_remove_title_filter_nav_menu', 10, 2);

        function trufi_add_title_filter_non_menu($items, $args) {
            // we are done working with menu, so add the title filter back
            add_filter('the_title', 'set_route_title', 10, 2);
            return $items;
        }

        // this filter fires after nav menu item creation is done
        add_filter('wp_nav_menu_items', 'trufi_add_title_filter_non_menu', 10, 2);


        global $wp_query;
        $wp_query->is_singular = true;
        $wp_query->is_page     = true;
        $wp_query->is_home     = false;
    }
}

/**
 * Disables other posts from being displayed when a route page is requested
 * Not sure why this happens, seems a bit of a hack but this has corrected it.
 * @param $query
 * @return void
 */
function disable_other_posts($query) {
    if (get_query_var('trufi_map_id') && get_query_var('trufi_map_name')) {
        if ($query->is_main_query() && !is_admin()) {
             $query->set('posts_per_page', '1');
            $query->set('p', 0);
        }
    }
}

add_action('pre_get_posts', 'disable_other_posts');
