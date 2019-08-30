<?php

use Carbon\Carbon;
use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Exceptions\RecoveryException;
use DansMaCulotte\Monetico\Requests\RecoveryRequest;
use PHPUnit\Framework\TestCase;

class RecoveryRequestTest extends TestCase
{
    public function testRecoveryConstruct()
    {
        $recovery = new RecoveryRequest([
            'dateTime' => Carbon::create(2019, 2, 1),
            'orderDate' => Carbon::create(2019, 1, 1),
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountToRecover' => 50,
            'amountRecovered' => 0,
            'amountLeft' => 50
        ]);

        $this->assertTrue($recovery instanceof RecoveryRequest);
    }

    public function testRecoveryUrl()
    {
        $url = RecoveryRequest::getUrl();

        $this->assertTrue($url === 'https://p.monetico-services.com/capture_paiement.cgi');

        $url = RecoveryRequest::getUrl(true);

        $this->assertTrue($url === 'https://p.monetico-services.com/test/capture_paiement.cgi');
    }

    public function testRecoveryConstructExceptionInvalidAmounts()
    {
        $this->expectExceptionObject(RecoveryException::invalidAmounts(100, 30, 0, 50));

        new RecoveryRequest([
            'dateTime' => Carbon::create(2019, 2, 1),
            'orderDate' => Carbon::create(2019, 1, 1),
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountToRecover' => 30,
            'amountRecovered' => 0,
            'amountLeft' => 50
        ]);
    }

    public function testRecoveryConstructExceptionInvalidDatetime()
    {
        $this->expectExceptionObject(Exception::invalidDatetime());

        new RecoveryRequest([
            'dateTime' => 'invalid',
            'orderDate' => Carbon::create(2019, 1, 1),
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountToRecover' => 50,
            'amountRecovered' => 0,
            'amountLeft' => 50
        ]);
    }

    public function testRecoveryConstructExceptionInvalidOrderDatetime()
    {
        $this->expectExceptionObject(Exception::invalidOrderDate());

        new RecoveryRequest([
            'dateTime' => Carbon::create(2019, 1, 1),
            'orderDate' => 'invalid',
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountToRecover' => 50,
            'amountRecovered' => 0,
            'amountLeft' => 50
        ]);
    }

    public function testRecoveryConstructExceptionInvalidReference()
    {
        $this->expectExceptionObject(Exception::invalidReference('thisisatoolongreference'));

        new RecoveryRequest([
            'dateTime' => Carbon::create(2019, 2, 1),
            'orderDate' => Carbon::create(2019, 1, 1),
            'reference' => 'thisisatoolongreference',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountToRecover' => 50,
            'amountRecovered' => 0,
            'amountLeft' => 50
        ]);
    }

    public function testRecoveryConstructExceptionInvalidLanguage()
    {
        $this->expectExceptionObject(Exception::invalidLanguage('English'));

        new RecoveryRequest([
            'dateTime' => Carbon::create(2019, 2, 1),
            'orderDate' => Carbon::create(2019, 1, 1),
            'reference' => 'ABC123',
            'language' => 'English',
            'currency' => 'EUR',
            'amount' => 100,
            'amountToRecover' => 50,
            'amountRecovered' => 0,
            'amountLeft' => 50
        ]);
    }

    public function testRecoveryOptions()
    {
        $recovery = new RecoveryRequest([
            'dateTime' => Carbon::create(2019, 2, 1),
            'orderDate' => Carbon::create(2019, 1, 1),
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountToRecover' => 50,
            'amountRecovered' => 0,
            'amountLeft' => 50
        ]);

        $recovery->setStopRecurrence();
        $this->assertEquals('oui', $recovery->stopRecurrence);

        $recovery->setFileNumber('12');
        $this->assertEquals(12, $recovery->fileNumber);

        $recovery->setInvoiceType('preauto');
        $this->assertEquals('preauto', $recovery->invoiceType);

        $recovery->setInvoiceType('noshow');
        $this->assertEquals('noshow', $recovery->invoiceType);

        $recovery->setPhone();
        $this->assertEquals('oui', $recovery->phone);
    }

    public function testSetInvoiceTypeExceptionInvalidInvoiceType()
    {
        $this->expectExceptionObject(Exception::invalidInvoiceType('invalid'));

        $recovery = new RecoveryRequest([
            'dateTime' => Carbon::create(2019, 2, 1),
            'orderDate' => Carbon::create(2019, 1, 1),
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountToRecover' => 50,
            'amountRecovered' => 0,
            'amountLeft' => 50
        ]);

        $recovery->setInvoiceType('invalid');
        $this->assertEquals('preauto', $recovery->invoiceType);
    }
}
