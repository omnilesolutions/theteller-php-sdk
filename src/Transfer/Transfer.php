<?php

namespace TheTeller\Transfer;

use Exception;
use TheTeller\Support\Http\HttpClientInterface;

abstract class Transfer{


    const THETELLER_TRANSFER_ENDPOINT = 'v1.1/transaction/process';
    const THETELLER_TRANSFER_STATUS_SUCCESSFUL = 'successful';
    const THETELLER_TRANSFER_STATUS_FAILED = 'failed';
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
     * @throws Exception
     */
    public function getReason()
    {
        if(empty($this->transferResponse['status']))
            throw new Exception('Transfer must be processed first.');

        if($this->transferResponse['status'] == self::THETELLER_TRANSFER_STATUS_SUCCESSFUL)
            return $this->transferResponse['reason'];

        else if($this->transferResponse['status'] == self::THETELLER_TRANSFER_STATUS_FAILED
            && isset($this->transferResponse['description'])){
            // Transfer failed.
            return $this->transferResponse['description'];
        }

        return 'Unknown reason.';
    }

}