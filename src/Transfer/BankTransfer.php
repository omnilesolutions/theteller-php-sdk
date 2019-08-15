<?php

namespace TheTeller\Transfer;

class BankTransfer extends Transfer{

    const THETELLER_BANK_PROCESSING_CODE = '404020';

    /**
     * Processes the bank transfer
     *
     * @param array $payload
     * @return bool
     */
    public function process($payload)
    {
        $this->transferResponse = $this->httpClient->post(Transfer::THETELLER_TRANSFER_ENDPOINT, array_merge($payload, [
            'processing_code' => static::THETELLER_BANK_PROCESSING_CODE,
            'r-switch' => 'FLT',
            'account_issuer' => 'GIP'
        ]));

        return $this->transferResponse['status'] === Transfer::THETELLER_TRANSFER_STATUS_SUCCESSFUL;
    }

    /**
     * Gets the bank transfer reference id
     *
     * @return string|null
     * @throws \Exception
     */
    public function getReferenceId()
    {
        if(!isset($this->transferResponse['reference_id']))
            throw new \Exception('Transfer must be processed first.');

        return $this->transferResponse['reference_id'];
    }

    /**
     * Gets the bank account name
     *
     * @return string|null
     * @throws \Exception
     */
    public function getAccountName()
    {
        if(!isset($this->transferResponse['account_name']))
            throw new \Exception('Transfer must be processed first.');

        return $this->transferResponse['account_name'];
    }


    /**
     * Confirms a bank transfer.
     *
     * @param string $merchantId
     * @return mixed
     */
    public function confirm($merchantId){
        return $this->httpClient->post(Transfer::THETELLER_TRANSFER_ENDPOINT, [
            'merchant_id' => $merchantId,
            'reference_id' => $this->getReferenceId()
        ]);
    }

}