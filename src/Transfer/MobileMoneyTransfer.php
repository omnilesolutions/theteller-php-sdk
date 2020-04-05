<?php

namespace TheTeller\Transfer;

use TheTeller\PadsDigits;

class MobileMoneyTransfer extends Transfer{

    use PadsDigits;

    const THETELLER_MOBILE_MONEY_PROCESSING_CODE = '404000';

    /**
     * Processes the mobile money transfer
     *
     * @param array $payload
     * @return bool
     */
    public function process($payload)
    {
        $this->transferResponse = $this->httpClient->post(Transfer::THETELLER_TRANSFER_ENDPOINT, array_merge(
            $this->pad($payload), [
            'processing_code' => static::THETELLER_MOBILE_MONEY_PROCESSING_CODE,
            'r-switch' => 'FLT',
            'amount' => str_pad($payload['amount'], 12, '0', STR_PAD_LEFT),
            'transaction_id' => str_pad($payload['transaction_id'], 12, '0', STR_PAD_LEFT),
        ]));

        return $this->transferResponse['status'] === Transfer::THETELLER_TRANSFER_STATUS_SUCCESSFUL;
    }

}