# Monetico PHP SDK

[![Latest Version](https://img.shields.io/packagist/v/DansMaCulotte/monetico-php.svg?style=flat-square)](https://packagist.org/packages/dansmaculotte/monetico-php)
[![Total Downloads](https://img.shields.io/packagist/dt/DansMaCulotte/monetico-php.svg?style=flat-square)](https://packagist.org/packages/dansmaculotte/monetico-php)
[![Build Status](https://img.shields.io/travis/DansMaCulotte/monetico-php/master.svg?style=flat-square)](https://travis-ci.org/dansmaculotte/monetico-php)
[![Quality Score](https://img.shields.io/scrutinizer/g/DansMaCulotte/monetico-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/dansmaculotte/monetico-php)
[![Code Coverage](https://img.shields.io/coveralls/github/DansMaCulotte/monetico-php.svg?style=flat-square)](https://coveralls.io/github/dansmaculotte/monetico-php)

This library aims to facilitate the usage of Monetico Service Methods

## Installation

### Requirements

- PHP 7.2

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
    'COMPANY_CODE'
);
```

### Payment

```php
use DansMaCulotte\Monetico\Requests\PaymentRequest;
use DansMaCulotte\Monetico\Resources\AddressResource;
use DansMaCulotte\Monetico\Resources\ClientResource;

$payment = new PaymentRequest([
    'reference' => 'ABCDEF123',
    'description' => 'Documentation',
    'language' => 'FR',
    'email' => 'john@snow.stark',
    'amount' => 42,
    'currency' => 'EUR',
    'datetime' => Carbon::create(2019, 1, 1),
    'successUrl' => 'http://localhost/thanks',
    'errorUrl' => 'http://localhost/oops',
]);

$address = new AddressResource('7 rue melingue', 'Caen', '14000', 'France');
$payment->setAddressBilling($address);
$address->setOptionalField('email', 'john@snow.stark');
$payment->setAddressShipping($address);

$client = new ClientResource('MR', 'John', 'Stark', 'Snow');
$payment->setClient($client);

$url = $payment->getUrl();
$fields = $monetico->getFields($payment);
```

```php
use DansMaCulotte\Monetico\Responses\PaymentResponse;
use DansMaCulotte\Monetico\Receips\PaymentReceipt;

// $data = json_decode($body, true);

$response = new PaymentResponse($data);

$result = $monetico->validate($response);

$receipt = new PaymentReceipt($result);
```

### Recovery

```php
use DansMaCulotte\Monetico\Requests\RecoveryRequest;
use DansMaCulotte\Monetico\Responses\RecoveryResponse;

$recovery = new RecoveryRequest([
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

$url = $recovery->getUrl();
$fields = $monetico->getFields($recovery);

$client = new GuzzleHttp\Client();
$data = $client->request('POST', $url, $fields);

// $data = json_decode($data, true);

$response = new RecoveryResponse($data);
```

### Cancel

```php
use DansMaCulotte\Monetico\Requests\CancelRequest;
use DansMaCulotte\Monetico\Responses\CancelResponse;

$cancel = new CancelRequest([
    'dateTime' => Carbon::create(2019, 2, 1),
    'orderDate' => Carbon::create(2019, 1, 1),
    'reference' => 'ABC123',
    'language' => 'FR',
    'currency' => 'EUR',
    'amount' => 100,
    'amountRecovered' => 0,
]);

$url = $cancel->getUrl();
$fields = $monetico->getFields($cancel);

$client = new GuzzleHttp\Client();
$data = $client->request('POST', $url, $fields);

// $data = json_decode($data, true);

$response = new CancelResponse($data);
```

### Refund

```php
use DansMaCulotte\Monetico\Requests\RefundRequest;
use DansMaCulotte\Monetico\Responses\RefundResponse;

$refund = new RefundRequest([
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

$url = $refund->getUrl();
$fields = $monetico->getFields($recovery);

$client = new GuzzleHttp\Client();
$data = $client->request('POST', $url, $fields);

// $data = json_decode($data, true);

$response = new RefundResponse($data);
```
## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.