<?php

use Api\TrufiApi;

class Trufi_Routes_Sitemap_Provider extends WP_Sitemaps_Provider {
    private   $apiUrl;
    private   $cache_lifetime_hours;
    private   $cache_file;
    protected $name;

    private string $base_path;

    public function __construct($apiUrl) {
        $this->apiUrl               = $apiUrl;
        $this->name                 = 'routes';
        $this->object_type          = 'routes';
        $this->cache_lifetime_hours = get_option(TRUFI_CACHE_TTL_OPTION);
        //$this->cache_lifetime_hours = 0;
        $this->cache_file           = plugin_dir_path(__FILE__) . '../cache/sitemap.json';

        $map_page_id     = get_option(TRUFI_MAP_PAGE_ID_OPTION);
        $this->base_path = ($map_page_id) ? get_page_uri($map_page_id) : 'routes';
    }

    public function get_name() {
        return $this->name;
    }

    public function get_max_num_pages($object_subtype = '') {
        return 1;
    }

    public function get_url_list($page_num, $object_subtype = '') {
        if (file_exists($this->cache_file) && (filemtime($this->cache_file) > (time() - 60 * 60 * $this->cache_lifetime_hours))) {
            $url_list = json_decode(file_get_contents($this->cache_file), true);
        } else {
            $url_list = $this->fetch_url_list_from_api();
            file_put_contents($this->cache_file, json_encode($url_list));
        }

        return $url_list;
    }

    private function fetch_url_list_from_api() {
        $trufiApi = new TrufiApi($this->apiUrl);
        $data     = $trufiApi->urlList();

        $url_list = [];
        foreach ($data['data']['patterns'] as $pattern) {
            $routeCode  = $pattern['code'];
            $routeName  = str_replace('→', $this::joinWord(), $pattern['route']['longName']);
            $routeName  = sanitize_title($routeName);
            $url_list[] = ['loc' => home_url("/$this->base_path/$routeName/$routeCode")];
        }

        return $url_list;
    }

    private static function joinWord() {
        $locale   = get_bloginfo("language");
        $language = explode("-", $locale)[0];
        switch ($language) {
            case "es":
                return "a";
            case "de":
                return "zu";
            case "fr":
                return "à";
            case "it":
                return "a";
            case "pt":
                return "para";
            case "ru":
                return "к";
            case "ja":
                return "に";
            case "ko":
                return "에";
            case "zh":
                return "到";
            case "ar":
                return "إلى";
            case "hi":
                return "को";
            case "bn":
                return "পর্যন্ত";
            case "pa":
                return "ਲਈ";
            case "te":
                return "కు";
            case "mr":
                return "करिता";
            case "ta":
                return "க்கு";
            case "gu":
                return "માટે";
            case "kn":
                return "ಗೆ";
            case "ml":
                return "വരെ";
            case "th":
                return "ถึง";
            case "vi":
                return "đến";
            case "tr":
                return "için";
            case "nl":
                return "naar";
            case "pl":
                return "do";
            case "sv":
                return "till";
            case "da":
                return "til";
            case "fi":
                return "saakka";
            case "no":
                return "til";
            case "cs":
                return "na";
            case "sk":
                return "na";
            case "hu":
                return "ig";
            case "el":
                return "προς";
            case "bg":
                return "към";
            case "ro":
                return "către";
            case "uk":
                return "до";
            case "hr":
                return "do";
            case "sr":
                return "ka";
            case "sl":
                return "do";
            case "lt":
                return "iki";
            case "lv":
                return "uz";
            case "et":
                return "kuni";
            case "en":
            default:
                return "to";
        }
    }
}
