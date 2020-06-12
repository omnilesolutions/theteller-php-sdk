<?php

namespace TheTeller;

/**
 * Created by PhpStorm.
 * User: brightantwiboasiako
 * Date: 8/15/19
 * Time: 4:26 PM
 */
trait PadsDigits{
    /**
     * @param $payload
     * @param int $digitsLength
     * @return array
     */
    public function pad($payload, $digitsLength = 12){
        return array_merge($payload, [
            'amount' => str_pad(100 * (float)$payload['amount'], $digitsLength, '0', STR_PAD_LEFT),
            'transaction_id' => str_pad($payload['transaction_id'], $digitsLength, '0', STR_PAD_LEFT),
        ]);
    }

}