<?php

namespace TheTeller;

use TheTeller\Checkout\Checkout;
use TheTeller\Support\Http\HttpClientInterface;
use TheTeller\Transfer\BankTransfer;
use TheTeller\Transfer\MobileMoneyTransfer;
use TheTeller\Transfer\Transfer;

class TheTeller{

    const THETELLER_MODE_LIVE = 'live';
    const THETELLER_MODE_TEST = 'test';

    /**
     * The supported transfer types
     *
     * @var array
     */
    private static $_supportedTransfers = [
        Transfer::THETELLER_TRANSFER_TYPE_MOBILE_MONEY,
        Transfer::THETELLER_TRANSFER_TYPE_BANK
    ];

    /**
     * Http client
     *
     * @var HttpClientInterface
     */
    private $http;

    /**
     * Constructor
     *
     * @param string $username
     * @param string $apiKey
     * @param string $mode
     */
    public function __construct(
        $username,
        $apiKey,
        $mode = self::THETELLER_MODE_LIVE
    )
    {
        $this->setupHttpClient($username, $apiKey, $mode);
    }

    /**
     * Requests a payment token for checkout
     *
     * @param array $payload
     * @return Checkout|null
     * @throws \Exception
     */
    public function requestPaymentToken($payload)
    {

        $checkout = new Checkout($this->http);

        if($checkout->process($payload)){
            return $checkout;
        }
        throw new \Exception($checkout->getReason());
    }


    /**
     * Performs a transfer
     *
     * @param string $method
     * @param array $payload
     * @return Transfer|mixed|null
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function transfer($method, $payload){
        
        if(!in_array($method, static::$_supportedTransfers))
            throw new \InvalidArgumentException('Unsupported transfer method: ' . $method);

        switch($method){
            case Transfer::THETELLER_TRANSFER_TYPE_MOBILE_MONEY:
                $this->transferToMobileMoney($payload);
                break;
            default:
                return $this->transferToBank($payload);
        }
    }


    /**
     * Makes a mobile money transfer
     *
     * @param array $payload
     * @return void
     * @throws \Exception
     */
    public function transferToMobileMoney($payload){
        $transfer = new MobileMoneyTransfer($this->http);
        if(!$transfer->process($payload)){
            throw new \Exception($transfer->getReason());
        }
    }

    /**
     * Makes a bank transfer
     *
     * @param array $payload
     * @return BankTransfer|null
     * @throws \Exception
     */
    public function transferToBank($payload){
        $transfer = new BankTransfer($this->http);
        if($transfer->process($payload)){
            return $transfer;
        }

        throw new \Exception($transfer->getReason());
    }


    /**
     * Sets up the http client
     *
     * @param string $username
     * @param string $apiKey
     * @param string $mode
     * @return void
     */
    private function setupHttpClient($username, $apiKey, $mode){
        // Set the mode
        if($mode === static::THETELLER_MODE_LIVE){
            HttpClient::live();
        }else{
            HttpClient::test();
        }

        $this->http = new HttpClient($username, $apiKey);
    }

}