# Monetico PHP SDK

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

$payment = new Payment(array(
    'reference' => 'ABCDEF123',
    'description' => 'Documentation',
    'language' => 'FR',
    'email' => 'john@snow.stark',
    'amount' => 42,
    'currency' => 'EUR',
    'datetime' => Carbon::create(2019, 1, 1),
));

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

#### Recovery

```php
Use DansMaCulotte\Monetico\Recovery\Recovery;

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
```

```php
use DansMaCulotte\Monetico\Recovery\Response;

// $data = json_decode($body, true);

$response = new Response($data);
```

#### Cancel

```php
use DansMaCulotte\Monetico\Cancel\Cancel;

$cancel = new Cancel([
    'dateTime' => Carbon::create(2019, 2, 1),
    'orderDate' => Carbon::create(2019, 1, 1),
    'reference' => 'ABC123',
    'language' => 'FR',
    'currency' => 'EUR',
    'amount' => 100,
    'amountRecovered' => 0,
]);
```

```php
use DansMaCulotte\Monetico\Cancel\Response;

// $data = json_decode($body, true);

$response = new Response($data);
```

#### Refund

```php
use DansMaCulotte\Monetico\Refund\Refund;

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
```

```php
use DansMaCulotte\Monetico\Refund\Response;

// $data = json_decode($body, true);

$response = new Response($data);
```
## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.