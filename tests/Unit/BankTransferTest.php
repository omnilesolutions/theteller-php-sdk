<?php

use PHPUnit\Framework\TestCase;
use TheTeller\Transfer\BankTransfer;
use TheTeller\Support\Http\HttpClientInterface;

class BankTransferTest extends TestCase{

    function test_it_can_process_bank_transfer(){
        $response = [
            'status' => 'successful',
            'account_name' => 'Kwame Obeng'
        ];

        $http = Mockery::mock(HttpClientInterface::class);
        $http->shouldReceive('post')->once()->andReturn($response);


        $payload = [
            'amount' => 20,
            'merchant_id' => 'THE-TELLER-MERCHANT-ID',
            'transaction_id' => '223'
        ];
        $transfer = new BankTransfer($http);
        $this->assertTrue($transfer->process($payload));
        $this->assertEquals($transfer->getAccountName(), 'Kwame Obeng');
    }
    
    
    function test_it_can_confirm_bank_transfer(){


        $referenceId = 'SOME-REFERENCE-ID';

        $response = [
            'status' => 'successful',
            'reference_id' => $referenceId
        ];

        $http = Mockery::mock(HttpClientInterface::class);
        $http->shouldReceive('post')->once()->andReturn($response);


        $merchantId = 'THE-TELLER-MERCHANT-ID';

        $payload = [
            'amount' => 20,
            'merchant_id' => $merchantId,
            'transaction_id' => '223'
        ];
        $transfer = new BankTransfer($http);
        $transfer->process($payload);

        $this->assertEquals(
            $transfer->confirm($merchantId),
            $response
        );

        
    }

}