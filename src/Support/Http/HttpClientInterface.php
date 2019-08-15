<?php

namespace TheTeller\Support\Http;

interface HttpClientInterface{

    /**
     * Makes a post request
     *
     * @param string $resourcePath
     * @param array $payload
     * @return mixed
     */
    public function post($resourcePath, $payload);
    /**
     * Makes a get request
     *
     * @param string $resourcePath
     * @return mixed
     */
    public function get($resourcePath);

}