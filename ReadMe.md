# Monetico PHP SDK

This library aims to facilitate the usage of Monetico Payment Service

## Installation

### Requirements

- PHP 7.0

You can install the package via composer:

``` bash
composer require dansmaculotte/monetico-php
```

## Usage

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

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.