<?php

namespace TheTeller\Transfer;

class MobileMoneyTransfer extends Transfer{

    const THETELLER_MOBILE_MONEY_PROCESSING_CODE = '404000';

    /**
     * Processes the mobile money transfer
     *
     * @param array $payload
     * @return bool
     */
    public function process($payload)
    {
        $this->transferResponse = $this->httpClient->post(Transfer::THETELLER_TRANSFER_ENDPOINT, array_merge($payload, [
            'processing_code' => static::THETELLER_MOBILE_MONEY_PROCESSING_CODE,
            'r-switch' => 'FLT'
        ]));

        return $this->transferResponse['status'] === Transfer::THETELLER_TRANSFER_STATUS_SUCCESSFUL;
    }

}