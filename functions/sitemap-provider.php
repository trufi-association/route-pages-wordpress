<?php

use App\Providers\TrufiRoutesSitemapProvider;

function register_trufi_sitemap_provider()
{
  require(plugin_dir_path(__FILE__) . '../App/Providers/TrufiRoutesSitemapProvider.php');

  $api_url = get_option(TRUFI_API_URL_OPTION);
  $provider = new TrufiRoutesSitemapProvider($api_url);
  wp_register_sitemap_provider($provider->get_name(), $provider);
}
add_filter('init', 'register_trufi_sitemap_provider');
