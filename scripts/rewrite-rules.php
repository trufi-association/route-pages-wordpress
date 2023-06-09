<?php

function trufi_maps_add_rewrite_rules()
{
  add_rewrite_tag('%trufi_map_id%', '([^/]+)');
  add_rewrite_tag('%trufi_map_name%', '([^/]+)');
  add_rewrite_rule('^routes/([^/]+)/([^/]+)/?', 'index.php?trufi_map_name=$matches[1]&trufi_map_id=$matches[2]', 'top');
  add_rewrite_endpoint('routes', EP_PERMALINK | EP_PAGES);
}
add_action('init', 'trufi_maps_add_rewrite_rules');
