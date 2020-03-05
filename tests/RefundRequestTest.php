<?php

use Carbon\Carbon;
use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Requests\RefundRequest;
use PHPUnit\Framework\TestCase;

class RefundRequestTest extends TestCase
{
    public function testRefundConstruct()
    {
        $refund = new RefundRequest([
            'dateTime' => Carbon::create(2019, 2, 1),
            'orderDate' => Carbon::create(2019, 1, 1),
            'recoveryDate' => Carbon::create(2019, 1, 1),
            'authorizationNumber' => '1222',
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'refundAmount' => 50,
            'maxRefundAmount' => 80,
        ]);

        $this->assertTrue($refund instanceof RefundRequest);
    }

    public function testRefundUrl()
    {
        $url = RefundRequest::getUrl();

        $this->assertSame($url, 'https://p.monetico-services.com/recredit_paiement.cgi');

        $url = RefundRequest::getUrl(true);

        $this->assertSame($url, 'https://p.monetico-services.com/test/recredit_paiement.cgi');
    }

    public function testRefundWithOptions()
    {
        $refund = new RefundRequest([
            'dateTime' => Carbon::create(2019, 2, 1),
            'orderDate' => Carbon::create(2019, 1, 1),
            'recoveryDate' => Carbon::create(2019, 1, 1),
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

        $this->assertInstanceOf(RefundRequest::class, $refund);
    }

    public function testRefundConstructExceptionInvalidDatetime()
    {
        $this->expectExceptionObject(Exception::invalidDatetime());
        new RefundRequest([
            'dateTime' => 'invalid',
            'orderDate' => Carbon::create(2019, 1, 1),
            'recoveryDate' => Carbon::create(2019, 1, 1),
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
        $this->expectExceptionObject(Exception::invalidOrderDate());
        new RefundRequest([
            'dateTime' => Carbon::create(2019, 1, 1),
            'orderDate' => 'invalid',
            'recoveryDate' => Carbon::create(2019, 1, 1),
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
        $this->expectExceptionObject(Exception::invalidRecoveryDate());
        new RefundRequest([
            'dateTime' => Carbon::create(2019, 1, 1),
            'orderDate' => Carbon::create(2019, 1, 1),
            'recoveryDate' =>'invalid',
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

        new RefundRequest([
            'dateTime' => Carbon::create(2019, 2, 1),
            'orderDate' => Carbon::create(2019, 1, 1),
            'recoveryDate' => Carbon::create(2019, 1, 1),
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

        new RefundRequest([
            'dateTime' => Carbon::create(2019, 2, 1),
            'orderDate' => Carbon::create(2019, 1, 1),
            'recoveryDate' => Carbon::create(2019, 1, 1),
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

        $refund = new RefundRequest([
            'dateTime' => Carbon::create(2019, 2, 1),
            'orderDate' => Carbon::create(2019, 1, 1),
            'recoveryDate' => Carbon::create(2019, 1, 1),
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
