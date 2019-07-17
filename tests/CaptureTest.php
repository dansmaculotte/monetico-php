<?php

use Carbon\Carbon;
use DansMaCulotte\Monetico\Capture\Capture;
use DansMaCulotte\Monetico\Exceptions\CaptureException;
use PHPUnit\Framework\TestCase;

class CaptureTest extends TestCase
{
    public function testCaptureConstruct()
    {
        $capture = new Capture([
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

        $this->assertTrue($capture instanceof Capture);
    }

    public function testCaptureConstructExceptionInvalidAmounts()
    {
        $this->expectExceptionObject(CaptureException::invalidAmounts(100, 30, 0, 50));

        new Capture([
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
        $this->expectExceptionObject(CaptureException::invalidDatetime());

        new Capture([
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
        $this->expectExceptionObject(CaptureException::invalidOrderDatetime());

        new Capture([
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
        $this->expectExceptionObject(CaptureException::invalidReference('thisisatoolongreference'));

        new Capture([
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
        $this->expectExceptionObject(CaptureException::invalidLanguage('English'));

        new Capture([
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
        $capture = new Capture([
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

        $capture->setStopRecurrence();
        $this->assertEquals('oui', $capture->stopRecurrence);

        $capture->setFileNumber('12');
        $this->assertEquals(12, $capture->fileNumber);

        $capture->setInvoiceType('preauto');
        $this->assertEquals('preauto', $capture->invoiceType);

        $capture->setInvoiceType('noshow');
        $this->assertEquals('noshow', $capture->invoiceType);

        $capture->setPhone();
        $this->assertEquals('oui', $capture->phone);
    }

    public function testSetInvoiceTypeExceptionInvalidInvoiceType()
    {
        $this->expectExceptionObject(CaptureException::invalidInvoiceType('invalid'));

        $capture = new Capture([
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

        $capture->setInvoiceType('invalid');
        $this->assertEquals('preauto', $capture->invoiceType);

    }

}