<?php

use PHPUnit\Framework\TestCase;
use TheTeller\Support\Http\HttpClientInterface;
use TheTeller\Checkout\Checkout;

class CheckoutTest extends TestCase{

    function test_it_can_get_payment_token(){

        $payload = [
            'merchant_id' => '',
            'transaction_id' => random_int(0, 1000),
            'desc' => 'Test Payment',
            'amount' => 20,
            'redirect_url' => 'https://foobar.com',
            'email' => 'foo@bar.com'
        ];

        $mockResponse = [
            'status' => 'success',
            'code' => 200,
            'reason' => 'Token successfully generated',
            'token' => 'eU1xSFN5Ky92MUt5dmpnT',
            'checkout_url' => 'https://test.theteller.net/checkout/checkout/eU1xSFN5Ky92MUt5dmpnT'
        ];

        $client = Mockery::mock(HttpClientInterface::class);
        $client->shouldReceive('post')->once()->andReturn($mockResponse);

        $checkout = new Checkout($client);
        
        $this->assertTrue($checkout->process($payload));
        $this->assertEquals($checkout->getCheckoutUrl(), $mockResponse['checkout_url']);
        $this->assertEquals($checkout->getToken(), $mockResponse['token']);
        $this->assertEquals($checkout->getReason(), 'Token successfully generated');

    }


}