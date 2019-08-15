<?php

use PHPUnit\Framework\TestCase;
use TheTeller\Support\Http\HttpClientInterface;
use TheTeller\Transfer\MobileMoneyTransfer;

class MobileMoneyTransferTest extends TestCase{

    function test_it_can_process_mobile_money_transfer(){

        $response = [
            'status' => 'successful'
        ];

        $http = Mockery::mock(HttpClientInterface::class);
        $http->shouldReceive('post')->once()->andReturn($response);


        $payload = [
            'amount' => 20,
            'merchant_id' => 'THE-TELLER-MERCHANT-ID'
        ];
        $mobileMoney = new MobileMoneyTransfer($http);
        $this->assertTrue($mobileMoney->process($payload));

    }

}