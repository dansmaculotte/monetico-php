<?php

use Carbon\Carbon;
use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Requests\CancelRequest;
use PHPUnit\Framework\TestCase;

class CancelRequestTest extends TestCase
{
    public function testCancelConstruct()
    {
        $cancel = new CancelRequest([
            'dateTime' => Carbon::create(2019, 2, 1),
            'orderDate' => Carbon::create(2019, 1, 1),
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountRecovered' => 0,
        ]);

        $this->assertInstanceOf(CancelRequest::class, $cancel);
    }

    public function testCancelUrl()
    {
        $url = CancelRequest::getUrl();

        $this->assertSame($url, 'https://p.monetico-services.com/capture_paiement.cgi');

        $url = CancelRequest::getUrl(true);

        $this->assertSame($url, 'https://p.monetico-services.com/test/capture_paiement.cgi');
    }

    public function testRecoveryConstructExceptionInvalidDatetime()
    {
        $this->expectExceptionObject(Exception::invalidDatetime());

        new CancelRequest([
            'dateTime' => 'invalid',
            'orderDate' => Carbon::create(2019, 1, 1),
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountRecovered' => 50,
        ]);
    }

    public function testRecoveryConstructExceptionInvalidOrderDatetime()
    {
        $this->expectExceptionObject(Exception::invalidOrderDate());

        new CancelRequest([
            'dateTime' => Carbon::create(2019, 1, 1),
            'orderDate' => 'invalid',
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountRecovered' => 50,
        ]);
    }

    public function testRecoveryConstructExceptionInvalidReference()
    {
        $this->expectExceptionObject(Exception::invalidReference('thisisatoolongreference'));

        new CancelRequest([
            'dateTime' => Carbon::create(2019, 2, 1),
            'orderDate' => Carbon::create(2019, 1, 1),
            'reference' => 'thisisatoolongreference',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountRecovered' => 50,
        ]);
    }

    public function testRecoveryConstructExceptionInvalidLanguage()
    {
        $this->expectExceptionObject(Exception::invalidLanguage('English'));

        new CancelRequest([
            'dateTime' => Carbon::create(2019, 2, 1),
            'orderDate' => Carbon::create(2019, 1, 1),
            'reference' => 'ABC123',
            'language' => 'English',
            'currency' => 'EUR',
            'amount' => 100,
            'amountRecovered' => 50,
        ]);
    }
}
