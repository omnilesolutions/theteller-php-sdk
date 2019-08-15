<?php

namespace TheTeller;

use GuzzleHttp\Client;
use TheTeller\Support\Http\HttpClientInterface;

class HttpClient implements HttpClientInterface{

    const THETELLER_TEST_BASE_ENDPOINT = 'https://test.theteller.net/';
    const THETELLER_LIVE_BASE_ENDPOINT = ' https://prod.theteller.net/';

    private static $_mode = TheTeller::THETELLER_MODE_TEST;

    /**
     * The Http engine
     *
     * @var Client
     */
    private $engine;

    /**
     * Constructs an http client
     *
     * @param string $username
     * @param string $apiKey
     */
    public function __construct($username, $apiKey)
    {
        $this->engine = new Client([
            'base_uri' => static::testing() ? static::THETELLER_TEST_BASE_ENDPOINT :
            static::THETELLER_LIVE_BASE_ENDPOINT,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Cache-Control' => 'no-cache'
            ],
            'auth' => [ $username, $apiKey ]
        ]);
    }

    /**
     * Sends a post request
     *
     * @param string $resourcePath
     * @param array $payload
     * @return array|mixed|null
     */
    public function post($resourcePath, $payload)
    {
        $response = $this->engine->post($resourcePath, [
            'json' => $payload
        ]);
        if($response->getBody()){
            return json_decode($response->getBody(), true);
        }
        return null;
    }

    /**
     * Sends a get request
     *
     * @param string $resourcePath
     * @return array|mixed|null
     */
    public function get($resourcePath){
        $response = $this->engine->get($resourcePath);
        if($response->getBody()){
            return json_decode($response->getBody(), true);
        }
        return null;
    }

    /**
     * Sets live mode
     *
     * @return void
     */
    public static function live(){
        static::$_mode = TheTeller::THETELLER_MODE_LIVE;
    }

    /**
     * Sets test mode
     *
     * @return void
     */
    public static function test(){
        static::$_mode = TheTeller::THETELLER_MODE_TEST;
    }

    /**
     * Checks if in test mode
     *
     * @return bool
     */
    private static function testing(){
        return static::$_mode === TheTeller::THETELLER_MODE_TEST;
    }

}