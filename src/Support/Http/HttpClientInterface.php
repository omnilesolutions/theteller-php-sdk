<?php

namespace TheTeller\Support\Http;

/**
 * Interface HttpClientInterface
 * @package TheTeller\Support\Http
 */
interface HttpClientInterface{

    /**
     * Makes a post request
     *
     * @param string $resourcePath
     * @param array $payload
     * @param array $options
     * @return mixed
     */
    public function post($resourcePath, $payload, $options = []);
    /**
     * Makes a get request
     *
     * @param string $resourcePath
     * @param array $options
     * @return mixed
     */
    public function get($resourcePath, $options = []);

}