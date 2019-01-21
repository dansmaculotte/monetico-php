<?php

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use DansMaCulotte\Monetico\Client;

require_once 'Credentials.php';

class ClientTest extends TestCase
{
    public function testClientConstruct()
    {
        $monetico = new Client(
            EPT_CODE,
            SECURITY_KEY,
            COMPANY_CODE,
            RETURN_URL,
            RETURN_SUCCESS_URL,
            RETURN_ERROR_URL
        );

        $monetico->setDebug();

        $paymentPayload = $monetico->generatePayload(
            "ABCDEF123",
            'PHPUnit',
            'FR',
            'john@english.fr',
            42.42,
            'EUR',
            Carbon::now()
        );

        print_r($paymentPayload);
    }
}