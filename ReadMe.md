# Monetico PHP SDK

[![Latest Version](https://img.shields.io/packagist/v/DansMaCulotte/monetico-php.svg?style=flat-square)](https://packagist.org/packages/dansmaculotte/monetico-php)
[![Total Downloads](https://img.shields.io/packagist/dt/DansMaCulotte/monetico-php.svg?style=flat-square)](https://packagist.org/packages/dansmaculotte/monetico-php)
[![Build Status](https://img.shields.io/github/workflow/status/dansmaculotte/monetico-php/run-tests?label=tests&style=flat-square)](https://github.com/dansmaculotte/monetico-php/actions?query=workflow%3Arun-tests)
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

### Purchase

```php
use DansMaCulotte\Monetico\Monetico;
use DansMaCulotte\Monetico\Requests\PurchaseRequest;
use DansMaCulotte\Monetico\Resources\BillingAddressResource;
use DansMaCulotte\Monetico\Resources\ShippingAddressResource;
use DansMaCulotte\Monetico\Resources\ClientResource;

$monetico = new Monetico(
    'EPT_CODE',
    'SECURITY_KEY',
    'COMPANY_CODE'
);

$purchase = new PurchaseRequest([
    'reference' => 'ABCDEF123',
    'description' => 'Documentation',
    'language' => 'FR',
    'email' => 'john@snow.stark',
    'amount' => 42,
    'currency' => 'EUR',
    'dateTime' => new DateTime(),
    'successUrl' => 'http://localhost/thanks',
    'errorUrl' => 'http://localhost/oops',
]);

$billingAddress = new BillingAddressResource([
    'name' => 'dans ma culotte',
    'addressLine1' => '42 rue des serviettes',
    'city' => 'Coupeville',
    'postalCode' => '42000',
    'country' => 'FR',
]);
$purchase->setBillingAddress($billingAddress);

$shippingAddress = new ShippingAddressResource([
    'name' => 'dans ma culotte',
    'addressLine1' => '42 rue des serviettes',
    'city' => 'Coupeville',
    'postalCode' => '42000',
    'country' => 'FR',
]);
$purchase->setShippingAddress($shippingAddress);

$client = new ClientResource([
    'civility' => 'Mr',
    'firstName' => 'John',
    'lastName' => 'Snow',
]);
$purchase->setClient($client);

$url = PurchaseRequest::getUrl();
$fields = $monetico->getFields($purchase);
```

```php
use DansMaCulotte\Monetico\Monetico;
use DansMaCulotte\Monetico\Responses\PurchaseResponse;
use DansMaCulotte\Monetico\Receipts\PurchaseReceipt;

$data = json_decode([/* bank request body */], true);

$monetico = new Monetico(
    'EPT_CODE',
    'SECURITY_KEY',
    'COMPANY_CODE'
);

$response = new PurchaseResponse($data);

$result = $monetico->validate($response);

$receipt = new PurchaseReceipt($result);
```

### Recovery

```php
use DansMaCulotte\Monetico\Monetico;
use DansMaCulotte\Monetico\Requests\RecoveryRequest;
use DansMaCulotte\Monetico\Responses\RecoveryResponse;

$monetico = new Monetico(
    'EPT_CODE',
    'SECURITY_KEY',
    'COMPANY_CODE'
);

$recovery = new RecoveryRequest([
    'reference' => 'AXCDEF123',
    'language' => 'FR',
    'amount' => 42.42,
    'amountToRecover' => 0,
    'amountRecovered' => 0,
    'amountLeft' => 42.42,
    'currency' => 'EUR',
    'orderDate' => new DateTime(),
    'dateTime' => new DateTime(),
]);

$url = RecoveryRequest::getUrl();
$fields = $monetico->getFields($recovery);

$client = new Http\Client();
$data = $client->request('POST', $url, $fields);

// $data = json_decode($data, true);

$response = new RecoveryResponse($data);
```

### Cancel

```php
use DansMaCulotte\Monetico\Monetico;
use DansMaCulotte\Monetico\Requests\CancelRequest;
use DansMaCulotte\Monetico\Responses\CancelResponse;

$monetico = new Monetico(
    'EPT_CODE',
    'SECURITY_KEY',
    'COMPANY_CODE'
);

$cancel = new CancelRequest([
    'dateTime' => new DateTime(),
    'orderDate' => new DateTime(),
    'reference' => 'ABC123',
    'language' => 'FR',
    'currency' => 'EUR',
    'amount' => 100,
    'amountRecovered' => 0,
]);

$url = CancelRequest::getUrl();
$fields = $monetico->getFields($cancel);

$client = new GuzzleHttp\Client();
$data = $client->request('POST', $url, $fields);

// $data = json_decode($data, true);

$response = new CancelResponse($data);
```

### Refund

```php
use DansMaCulotte\Monetico\Monetico;
use DansMaCulotte\Monetico\Requests\RefundRequest;
use DansMaCulotte\Monetico\Responses\RefundResponse;

$monetico = new Monetico(
    'EPT_CODE',
    'SECURITY_KEY',
    'COMPANY_CODE'
);

$refund = new RefundRequest([
    'dateTime' => new DateTime(),
    'orderDatetime' => new DateTime(),
    'recoveryDatetime' => new DateTime(),
    'authorizationNumber' => '1222',
    'reference' => 'ABC123',
    'language' => 'FR',
    'currency' => 'EUR',
    'amount' => 100,
    'refundAmount' => 50,
    'maxRefundAmount' => 80,
]);

$url = RefundRequest::getUrl();
$fields = $monetico->getFields($refund);

$client = new GuzzleHttp\Client();
$data = $client->request('POST', $url, $fields);

// $data = json_decode($data, true);

$response = new RefundResponse($data);
```
## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
