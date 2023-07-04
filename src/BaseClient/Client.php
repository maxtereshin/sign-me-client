<?php

namespace Maxtereshin\SignMeClient\BaseClient;

use Exception;
use Illuminate\Support\Facades\Http;

class Client
{

    private string $api_key;
    private string $base_url;
    public function __construct($base_url, $api_key)
    {
        $this->api_key = $api_key;
        $this->base_url = $base_url;
    }

    /**
     * @throws Exception
     */
    public function request(string $url, array $params = [], $json = true, string $method = 'post')
    {
        $params['api_key'] = $this->api_key;
        $response = Http::acceptJson();
        if(!$json) {
            $response = $response->asForm();
        }
        $response = $response->$method($this->base_url . $url, $params);

//        dd($response->body());

        $code = $response->getStatusCode();
        if(($code !== 200 && $code !== 201) || !$response->json()) {
            throw new Exception("Error exception: " . $response->body());
        }

        return $response->json();
    }
}