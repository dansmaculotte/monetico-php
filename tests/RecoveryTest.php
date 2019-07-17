<?php

use Carbon\Carbon;
use DansMaCulotte\Monetico\Capture\Recovery;
use DansMaCulotte\Monetico\Exceptions\RecoveryException;
use PHPUnit\Framework\TestCase;

class RecoveryTest extends TestCase
{
    public function testCaptureConstruct()
    {
        $recovery = new Recovery([
            'datetime' => Carbon::create(2019, 2, 1),
            'orderDatetime' => Carbon::create(2019, 1, 1),
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountToCapture' => 50,
            'amountCaptured' => 0,
            'amountLeft' => 50
        ]);

        $this->assertTrue($recovery instanceof Recovery);
    }

    public function testCaptureConstructExceptionInvalidAmounts()
    {
        $this->expectExceptionObject(RecoveryException::invalidAmounts(100, 30, 0, 50));

        new Recovery([
            'datetime' => Carbon::create(2019, 2, 1),
            'orderDatetime' => Carbon::create(2019, 1, 1),
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountToCapture' => 30,
            'amountCaptured' => 0,
            'amountLeft' => 50
        ]);
    }

    public function testCaptureConstructExceptionInvalidDatetime()
    {
        $this->expectExceptionObject(RecoveryException::invalidDatetime());

        new Recovery([
            'datetime' => 'invalid',
            'orderDatetime' => Carbon::create(2019, 1, 1),
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountToCapture' => 50,
            'amountCaptured' => 0,
            'amountLeft' => 50
        ]);
    }

    public function testCaptureConstructExceptionInvalidOrderDatetime()
    {
        $this->expectExceptionObject(RecoveryException::invalidOrderDatetime());

        new Recovery([
            'datetime' => Carbon::create(2019, 1, 1),
            'orderDatetime' => 'invalid',
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountToCapture' => 50,
            'amountCaptured' => 0,
            'amountLeft' => 50
        ]);
    }

    public function testCaptureConstructExceptionInvalidReference()
    {
        $this->expectExceptionObject(RecoveryException::invalidReference('thisisatoolongreference'));

        new Recovery([
            'datetime' => Carbon::create(2019, 2, 1),
            'orderDatetime' => Carbon::create(2019, 1, 1),
            'reference' => 'thisisatoolongreference',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountToCapture' => 50,
            'amountCaptured' => 0,
            'amountLeft' => 50
        ]);
    }

    public function testCaptureConstructExceptionInvalidLanguage()
    {
        $this->expectExceptionObject(RecoveryException::invalidLanguage('English'));

        new Recovery([
            'datetime' => Carbon::create(2019, 2, 1),
            'orderDatetime' => Carbon::create(2019, 1, 1),
            'reference' => 'ABC123',
            'language' => 'English',
            'currency' => 'EUR',
            'amount' => 100,
            'amountToCapture' => 50,
            'amountCaptured' => 0,
            'amountLeft' => 50
        ]);
    }

    public function testCaptureOptions()
    {
        $recovery = new Recovery([
            'datetime' => Carbon::create(2019, 2, 1),
            'orderDatetime' => Carbon::create(2019, 1, 1),
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountToCapture' => 50,
            'amountCaptured' => 0,
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
        $this->expectExceptionObject(RecoveryException::invalidInvoiceType('invalid'));

        $recovery = new Recovery([
            'datetime' => Carbon::create(2019, 2, 1),
            'orderDatetime' => Carbon::create(2019, 1, 1),
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountToCapture' => 50,
            'amountCaptured' => 0,
            'amountLeft' => 50
        ]);

        $recovery->setInvoiceType('invalid');
        $this->assertEquals('preauto', $recovery->invoiceType);

    }

}