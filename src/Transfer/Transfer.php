<?php

namespace TheTeller\Transfer;

use TheTeller\Support\Http\HttpClientInterface;

abstract class Transfer{


    const THETELLER_TRANSFER_ENDPOINT = '/v1.1/transaction/process';
    const THETELLER_TRANSFER_STATUS_SUCCESSFUL = 'successful';
    const THETELLER_TRANSFER_TYPE_MOBILE_MONEY = 'mobile-money';
    const THETELLER_TRANSFER_TYPE_BANK = 'bank';

    /**
     * Http client
     *
     * @var HttpClientInterface
     */
    protected $httpClient;

    /**
     * Transfer response
     *
     * @var array
     */
    protected $transferResponse;

    /**
     * Constructor
     *
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->httpClient = $client;
    }

    public abstract function process($payload);

    /**
     * Gets the transfer reason
     *
     * @return string|null
     */
    public function getReason()
    {
        if(!isset($this->transferResponse['reason']))
            throw new \Exception('Transfer must be processed first.');

        return $this->transferResponse['reason'];
    }

}