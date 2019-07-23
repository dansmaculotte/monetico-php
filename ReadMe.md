# Monetico PHP SDK

[![Latest Version](https://img.shields.io/packagist/v/DansMaCulotte/monetico-php.svg?style=flat-square)](https://packagist.org/packages/dansmaculotte/monetico-php)
[![Total Downloads](https://img.shields.io/packagist/dt/DansMaCulotte/monetico-php.svg?style=flat-square)](https://packagist.org/packages/dansmaculotte/monetico-php)
[![Build Status](https://img.shields.io/travis/DansMaCulotte/monetico-php/master.svg?style=flat-square)](https://travis-ci.org/dansmaculotte/monetico-php)
[![Quality Score](https://img.shields.io/scrutinizer/g/DansMaCulotte/monetico-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/dansmaculotte/monetico-php)
[![Code Coverage](https://img.shields.io/coveralls/github/DansMaCulotte/monetico-php.svg?style=flat-square)](https://coveralls.io/github/dansmaculotte/monetico-php)

This library aims to facilitate the usage of Monetico Service Methods

## Installation

### Requirements

- PHP 7.0

You can install the package via composer:

```bash
composer require dansmaculotte/monetico-php
```

## Usage

### Monetico

```php
use DansMaCulotte\Monetico\Monetico;

$monetico = new Monetico(
    'EPT_CODE',
    'SECURITY_KEY',
    'COMPANY_CODE',
    'RETURN_URL',
    'RETURN_SUCCESS_URL',
    'RETURN_ERROR_URL'
);
```

### Payment

```php
use DansMaCulotte\Monetico\Payment\Payment;
use DansMaCulotte\Monetico\Resources\AddressBilling;
use DansMaCulotte\Monetico\Resources\AddressShipping;
use DansMaCulotte\Monetico\Resources\Client;

$payment = new Payment(array(
    'reference' => 'ABCDEF123',
    'description' => 'Documentation',
    'language' => 'FR',
    'email' => 'john@snow.stark',
    'amount' => 42,
    'currency' => 'EUR',
    'datetime' => Carbon::create(2019, 1, 1),
));

$addressBilling = new AddressBilling('7 rue melingue', 'Caen', '14000', 'France');
$payment->setAddressBilling($addressBilling);

$addressShipping = new AddressShipping('7 rue melingue', 'Caen', '14000', 'France');
$payment->setAddressShipping($addressShipping);

$client = new Client('MR', 'John', 'Stark', 'Snow');
$payment->setClient($client);

$url = $monetico->getPaymentUrl();
$fields = $monetico->getPaymentFields($payment);
```

```php
Use DansMaCulotte\Monetico\Payment\Response;
use DansMaCulotte\Monetico\Payment\Receipt;

// $data = json_decode($body, true);

$response = new Response($data);

$result = $monetico->validateSeal($response);

$receipt = new Receipt($result);
```

### Recovery

```php
Use DansMaCulotte\Monetico\Recovery\Recovery;
use DansMaCulotte\Monetico\Recovery\Response;

$recovery = new Recovery([
    'reference' => 'AXCDEF123',
    'language' => 'FR',
    'amount' => 42.42,
    'amountToRecover' => 0,
    'amountRecovered' => 0,
    'amountLeft' => 42.42,
    'currency' => 'EUR',
    'orderDate' => Carbon::create(2019, 07, 17),
    'dateTime' => Carbon::create(2019, 07, 17),
]);

$url = $monetico->getRecoveryUrl();
$fields = $monetico->getRecoveryFields($recovery);

$client = new GuzzleHttp\Client();
$data = $client->request('POST', $url, $fields);

// $data = json_decode($data, true);

$response = new Response($data);
```

### Cancel

```php
use DansMaCulotte\Monetico\Cancel\Cancel;
use DansMaCulotte\Monetico\Cancel\Response;

$cancel = new Cancel([
    'dateTime' => Carbon::create(2019, 2, 1),
    'orderDate' => Carbon::create(2019, 1, 1),
    'reference' => 'ABC123',
    'language' => 'FR',
    'currency' => 'EUR',
    'amount' => 100,
    'amountRecovered' => 0,
]);

$url = $monetico->getCancelUrl();
$fields = $monetico->getCancelFields($recovery);

$client = new GuzzleHttp\Client();
$data = $client->request('POST', $url, $fields);

// $data = json_decode($data, true);

$response = new Response($data);
```

### Refund

```php
use DansMaCulotte\Monetico\Refund\Refund;
use DansMaCulotte\Monetico\Refund\Response;

$refund = new Refund([
    'datetime' => Carbon::create(2019, 2, 1),
    'orderDatetime' => Carbon::create(2019, 1, 1),
    'recoveryDatetime' => Carbon::create(2019, 1, 1),
    'authorizationNumber' => '1222',
    'reference' => 'ABC123',
    'language' => 'FR',
    'currency' => 'EUR',
    'amount' => 100,
    'refundAmount' => 50,
    'maxRefundAmount' => 80,
]);

$url = $monetico->getRefundUrl();
$fields = $monetico->getRefundFields($recovery);

$client = new GuzzleHttp\Client();
$data = $client->request('POST', $url, $fields);

// $data = json_decode($data, true);

$response = new Response($data);
```
## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.