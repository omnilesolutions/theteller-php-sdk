<?php

namespace TheTeller\Checkout;

use TheTeller\PadsDigits;
use TheTeller\Support\Http\HttpClientInterface;

class Checkout
{

    use PadsDigits;

    const THETELLER_CHECKOUT_ENDPOINT = 'checkout/initiate';
    const THETELLER_CHECKOUT_SUCCESS_STATUS = 'success';
    const THETELLER_CHECKOUT_TOKEN_URL = 'checkout_url';
    const THETELLER_CHECKOUT_TOKEN = 'token';
    const THETELLER_CHECKOUT_REASON = 'reason';

    /**
     * Http client
     *
     * @var HttpClientInterface
     */
    private $httpClient;
    /**
     * Token response
     *
     * @var array
     */
    private $tokenResponse;

    /**
     * Constructs a checkout
     *
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->httpClient = $client;
    }

    /**
     * Processes the checkout
     *
     * @param array $payload
     * @return bool
     */
    public function process($payload)
    {
        $this->tokenResponse = $this->httpClient->post(
            static::THETELLER_CHECKOUT_ENDPOINT,
            $this->pad($payload)
        );

        return isset($this->tokenResponse['status']) &&  $this->get('status') ===
        static::THETELLER_CHECKOUT_SUCCESS_STATUS;
    }

    /**
     * Gets the checkout url for successful checkouts
     *
     * @return string
     */
    public function getCheckoutUrl()
    {
        return $this->get(static::THETELLER_CHECKOUT_TOKEN_URL);
    }

    public function proceedToPayment(){
        header('Location: ' . $this->getCheckoutUrl());
        exit;
    }

    /**
     * Gets the payment token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->get(static::THETELLER_CHECKOUT_TOKEN);
    }

    /**
     * Gets the checkout reason
     *
     * @return string
     */
    public function getReason(){
        if(!isset($this->tokenResponse[static::THETELLER_CHECKOUT_REASON])){
            // We don't have a reason, probably validation error
            if(!empty($this->tokenResponse)){
                return array_values($this->tokenResponse)[0][0];
            }else{
                return 'Unknown error.';
            }
        }

        return $this->get(static::THETELLER_CHECKOUT_REASON);
    }

    /**
     * Gets a payment token by field
     *
     * @param string $key
     * @return string|mixed
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function get($key)
    {
        if (is_null($this->tokenResponse))
            throw new \Exception('Checkout needs to be processed first.');

        if (!isset($this->tokenResponse[$key]))
            throw new \InvalidArgumentException('Unknown key: ' . $key . ' requested.');

        return $this->tokenResponse[$key];
    }
}
