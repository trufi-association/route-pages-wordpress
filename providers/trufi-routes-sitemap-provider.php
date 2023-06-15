<?php

class Trufi_Routes_Sitemap_Provider extends WP_Sitemaps_Provider
{
  private $apiUrl;
  private $cache_lifetime_hours;
  private $cache_file;
  protected $name;

  public function __construct($apiUrl)
  {
    $this->apiUrl = $apiUrl;
    $this->name = 'routes';
    $this->object_type = 'routes';
    $this->cache_lifetime_hours = get_option(TRUFI_CACHE_TTL_OPTION);
    $this->cache_file = plugin_dir_path(__FILE__) . '../cache/sitemap.json';
  }

  public function get_name()
  {
    return $this->name;
  }

  public function get_max_num_pages($object_subtype = '')
  {
    return 1;
  }

  public function get_url_list($page_num, $object_subtype = '')
  {
    if (file_exists($this->cache_file) && (filemtime($this->cache_file) > (time() - 60 * 60 * $this->cache_lifetime_hours))) {
      $url_list = json_decode(file_get_contents($this->cache_file), true);
    } else {
      $url_list = $this->fetch_url_list_from_api();
      file_put_contents($this->cache_file, json_encode($url_list));
    }

    return $url_list;
  }

  private function fetch_url_list_from_api()
  {
    $url = $this->apiUrl;
    $query = '{
      patterns{
        code, route{ longName }
      }
    }';
    $data = $this->get_graphql_data($url, $query);

    $url_list = [];
    foreach ($data['data']['patterns'] as $pattern) {
      $routeCode = $pattern['code'];
      $routeName = sanitize_title($pattern['route']['longName']);
      $url_list[] = ['loc' => home_url("/routes/$routeName/$routeCode")];
    }

    return $url_list;
  }

  private function get_graphql_data($url, $query)
  {
    $args = array(
      'headers' => array(
        'Content-Type' => 'application/json',
      ),
      'body' => json_encode(array(
        'query' => $query
      )),
      'method'  => 'POST',
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
      return null;
    } else {
      $body = wp_remote_retrieve_body($response);
      $data = json_decode($body, true);
      return $data;
    }
  }
}
