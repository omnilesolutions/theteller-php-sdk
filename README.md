# theteller-php-sdk [![Build Status](https://travis-ci.org/brightantwiboasiako/theteller-php-sdk.svg?branch=master)](https://travis-ci.org/brightantwiboasiako/theteller-php-sdk)

PHP SDK for theteller Payments API

## Introduction
To use this sdk, you'll need an account from [TheTeller](https://theteller.net). Create one [here](https://theteller.net/signup) if you don't already have one.
Also, this sdk follows TheTeller documentation [https://www.theteller.net/documentation](https://www.theteller.net/documentation)

## Installation
This sdk can be installed using [composer](https://getcomposer.org).

```
composer require omnilesolutions/theteller-php-sdk
```

## Authentication
TheTeller uses [basic HTTP authentication](http://www.ietf.org/rfc/rfc2069.txt).
 You'll need your API username and key, which are available under My Account section of your TheTeller account dashboard.

 ## TheTeller Checkout
 
 To perform a checkout, you first need to get a payment token with an order payload.

 ```php

// Get the teller instance.
 
$teller = new TheTeller\TheTeller($username, $apiKey)
 
// There is a third argument for mode which is LIVE by default. If you want to
// use this sdk in test mode, instantiate the teller as
// $teller = new TheTeller\TheTeller($username, $apiKey, TheTeller\TheTeller::THETELLER_MODE_TEST)
 
// Create order payload
$order = [
    'merchant_id' => 'YOUR-MERCHANT-ID', // available on TheTeller dashboard, under My Account.
    'transaction_id' => 'A UNIQUE TRANSACTION ID',
    'desc' => 'DESCRIPTION OF YOUR ORDER',
    'amount' => '20', // Amount to charge the customer
    'redirect_url' => 'YOUR REDIRECT URL',
    'email' => 'EMAIL ADDRESS OF CUSTOMER'
];

// Process the checkout
$checkout = $teller->requestPaymentToken($order);

// If successful, the returned $checkout will be an instance of TheTeller\Checkout\Checkout

// You now have to redirect the customer to TheTeller to make payment.
// This will take the customer to TheTeller.
$checkout->proceedToPayment();

// When the payment is completed by the customer, TheTeller will
// redirect them back to your provided 'redirect_url' in the order
// with query parameters indicating the status of the payment.

// The response looks like this:
// https://redirect_url?code=000&status=successful&reason=transaction20%successful&transaction_id=000000000000

 ```

 ## TheTeller Funds Transfer

You can transfer funds from your Merchant Float Account which can be created from TheTeller dashboard. More information [here](https://theteller.net/documentation#theTeller_Standard).

### Mobile Money Transfer

```php

// Get the teller instance.

$teller = new TheTeller\TheTeller($username, $apiKey);
// There is a third argument for mode which is LIVE by default. If you want to
// use this sdk in test mode, instantiate the teller as
// $teller = new TheTeller\TheTeller($username, $apiKey, TheTeller\TheTeller::THETELLER_MODE_TEST)

// Create transfer payload
$payload = [
    'amount' => '20', // The transfer amount
    'merchant_id' => 'YOUR-MERCHANT-ID', // available on TheTeller dashboard, under My Account.
    'account_number' => '0240000000', // The mobile account number
    'account_issuer' => 'MTN', // The mobile money network
    'transaction_id' => 'A UNIQUE TRANSACTION ID',
    'desc' => 'DESCRIPTION OF TRANSFER',
    'pass_code' => 'UNIQUE FLOAT ACCOUNT PASSCODE'
]

// Process the transfer
try{

    $teller->transfer('mobile-money', $payload);

    // Transfer is successful

}catch(\Exception $e){
    var_dump($e->getMessage()); // The reason for the failure
}

```

### Bank Transfer

```php

// Get the teller instance.

$teller = new TheTeller\TheTeller($username, $apiKey);
// There is a third argument for mode which is LIVE by default. If you want to
// use this sdk in test mode, instantiate the teller as
// $teller = new TheTeller\TheTeller($username, $apiKey, TheTeller\TheTeller::THETELLER_MODE_TEST)

// Create transfer payload
$payload = [
    'amount' => '20', // The transfer amount
    'merchant_id' => 'YOUR-MERCHANT-ID', // available on TheTeller dashboard, under My Account.
    'account_bank' => 'GCB', // List of supported banks [here](https://theteller.net/documentation#theTeller_Standard)
    'account_number' => '0082000141685300', // The bank account number
    'transaction_id' => 'A UNIQUE TRANSACTION ID',
    'desc' => 'DESCRIPTION OF TRANSFER',
    'pass_code' => 'UNIQUE FLOAT ACCOUNT PASSCODE'
]

// Process the transfer
try{

    // This step performs an enquiry of the bank account

    $transfer = $teller->transfer('bank', $payload);

    // Enquiry was successfull.
    // You can get the bank account name to ensure
    // that the transfer is going to the intended account.
    $name = $transfer->getAccountName();

    // Complete the transfer
    $transfer->confirm($merchantId); // Your TheTeller Merchant ID

}catch(\Exception $e){
    var_dump($e->getMessage()); // The reason for the failure
}
```

### Get Transaction Status

```php

// Get the teller instance.

$teller = new TheTeller\TheTeller($username, $apiKey);
// There is a third argument for mode which is LIVE by default. If you want to
// use this sdk in test mode, instantiate the teller as
// $teller = new TheTeller\TheTeller($username, $apiKey, TheTeller\TheTeller::THETELLER_MODE_TEST)

// Get status
try{

    $response = $teller->getTransactionStatus($transactionId, $merchantId);

}catch(\Exception $e){
    var_dump($e->getMessage()); // The reason for the failure
}
```

### Prerequisites
* PHP 7.0 or above
* [curl](https://secure.php.net/manual/en/book.curl.php) and
[openssl](https://secure.php.net/manual/en/book.openssl.php)
extensions.

## Contributing
Contributions are welcome! Please do a PR with any bug-fixes or email me at [brightantwiboasiako@aol.com](mailto:brightantwiboasiako@aol.com) 
for a long term commitment.

## License
This open-source project is licensed under the [MIT LICENSE](https://opensource.org/licenses/MIT)
