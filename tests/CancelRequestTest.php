<?php

use Carbon\Carbon;
use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Exceptions\RecoveryException;
use DansMaCulotte\Monetico\Requests\CancelRequest;
use PHPUnit\Framework\TestCase;

class CancelRequestTest extends TestCase
{
    public function testRecoveryConstruct()
    {
        $cancel = new CancelRequest([
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

        $this->assertTrue($cancel instanceof CancelRequest);
    }

    public function testRecoveryUrl()
    {
        $url = CancelRequest::getUrl();

        $this->assertTrue($url === 'https://p.monetico-services.com/capture_paiement.cgi');

        $url = CancelRequest::getUrl(true);

        $this->assertTrue($url === 'https://p.monetico-services.com/test/capture_paiement.cgi');
    }

    public function testRecoveryConstructExceptionInvalidAmounts()
    {
        $this->expectExceptionObject(RecoveryException::invalidAmounts(100, 30, 0, 50));

        new CancelRequest([
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

        new CancelRequest([
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

        new CancelRequest([
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

        new CancelRequest([
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

        new CancelRequest([
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
        $cancel = new CancelRequest([
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

        $cancel->setStopRecurrence();
        $this->assertEquals('oui', $cancel->stopRecurrence);

        $cancel->setFileNumber('12');
        $this->assertEquals(12, $cancel->fileNumber);

        $cancel->setInvoiceType('preauto');
        $this->assertEquals('preauto', $recovery->invoiceType);

        $recovery->setInvoiceType('noshow');
        $this->assertEquals('noshow', $recovery->invoiceType);

        $recovery->setPhone();
        $this->assertEquals('oui', $recovery->phone);
    }

    public function testSetInvoiceTypeExceptionInvalidInvoiceType()
    {
        $this->expectExceptionObject(Exception::invalidInvoiceType('invalid'));

        $recovery = new CancelRequest([
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
