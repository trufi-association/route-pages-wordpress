<?php

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
