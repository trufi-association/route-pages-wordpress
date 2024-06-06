<?php

namespace Api;

class TrufiApi {
    public string $apiUrl;

    public function __construct($apiUrl) {
        $this->apiUrl = $apiUrl;
    }

    public function query($query, $variables = null) {
        $body['query'] = $query;
        if ($variables) {
            $body['variables'] = $variables;
        }
        $response = wp_remote_post($this->apiUrl,
            ['body'    => json_encode($body),
             'headers' => [
                 'Content-Type' => 'application/json',
                 'Accept'       => 'application/json',
             ],
            ]);

        if (is_wp_error($response)) {
            return null;
        } else {
            $_body = wp_remote_retrieve_body($response);
            return json_decode($_body, true);
        }
    }

    public function fetchRoute($routeId) {
        $query = 'query parking($id: String!) { 
                    pattern(id: $id) { 
                        route { id, shortName, longName }, 
                        geometry { lat, lon } 
                    } 
                 }';
        $variables = '{"id":"'.$routeId.'"}';

        return $this->query($query, $variables);
    }

    public function urlList(): array|null {
        $body = '{
            patterns{
                code, route{ longName }
            }
        }';
        return $this->query($body);
    }
}
