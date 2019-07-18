<?php

use Carbon\Carbon;
use DansMaCulotte\Monetico\Refund\Refund;
use PHPUnit\Framework\TestCase;
use DansMaCulotte\Monetico\Exceptions\Exception;

class RefundTest extends TestCase
{
    public function testRefundConstruct()
    {
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

        $this->assertTrue($refund instanceof Refund);
    }

    public function testRefundWithOptions()
    {
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

        $refund->setFileNumber('123');
        $this->assertEquals('123', $refund->fileNumber);

        $refund->setInvoiceType('preauto');
        $this->assertEquals('preauto', $refund->invoiceType);

        $this->assertTrue($refund instanceof Refund);
    }

    public function testRefundConstructExceptionInvalidDatetime()
    {
        $this->expectExceptionObject(Exception::invalidDatetime());
        new Refund([
            'datetime' => 'invalid',
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
    }

    public function testRefundConstructExceptionInvalidOrderDatetime()
    {
        $this->expectExceptionObject(Exception::invalidOrderDatetime());
        new Refund([
            'datetime' => Carbon::create(2019, 1, 1),
            'orderDatetime' => 'invalid',
            'recoveryDatetime' => Carbon::create(2019, 1, 1),
            'authorizationNumber' => '1222',
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'refundAmount' => 50,
            'maxRefundAmount' => 80,
        ]);
    }

    public function testRefundConstructExceptionInvalidRecoveryDatetime()
    {
        $this->expectExceptionObject(Exception::invalidRecoveryDatetime());
        new Refund([
            'datetime' => Carbon::create(2019, 1, 1),
            'orderDatetime' => Carbon::create(2019, 1, 1),
            'recoveryDatetime' =>'invalid',
            'authorizationNumber' => '1222',
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'refundAmount' => 50,
            'maxRefundAmount' => 80,
        ]);
    }

    public function testRefundConstructExceptionInvalidReference()
    {
        $this->expectExceptionObject(Exception::invalidReference('thisisatoolongreference'));

        new Refund([
            'datetime' => Carbon::create(2019, 2, 1),
            'orderDatetime' => Carbon::create(2019, 1, 1),
            'recoveryDatetime' => Carbon::create(2019, 1, 1),
            'authorizationNumber' => '1222',
            'reference' => 'thisisatoolongreference',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'refundAmount' => 50,
            'maxRefundAmount' => 80,
        ]);
    }

    public function testRefundConstructExceptionInvalidLanguage()
    {
        $this->expectExceptionObject(Exception::invalidLanguage('invalid'));

        new Refund([
            'datetime' => Carbon::create(2019, 2, 1),
            'orderDatetime' => Carbon::create(2019, 1, 1),
            'recoveryDatetime' => Carbon::create(2019, 1, 1),
            'authorizationNumber' => '1222',
            'reference' => 'ABC123',
            'language' => 'invalid',
            'currency' => 'EUR',
            'amount' => 100,
            'refundAmount' => 50,
            'maxRefundAmount' => 80,
        ]);
    }

    public function testRefundConstructExceptionInvalidInvoiceType()
    {
        $this->expectExceptionObject(Exception::invalidInvoiceType('invalid'));

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

        $refund->setInvoiceType('invalid');
    }



}