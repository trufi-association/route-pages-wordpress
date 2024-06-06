<?php

function register_trufi_sitemap_provider()
{
  require(plugin_dir_path(__FILE__) . '../providers/trufi-routes-sitemap-provider.php');

  $api_url = get_option(TRUFI_API_URL_OPTION);
  $provider = new Trufi_Routes_Sitemap_Provider($api_url);
  wp_register_sitemap_provider($provider->get_name(), $provider);
}
add_filter('init', 'register_trufi_sitemap_provider');
