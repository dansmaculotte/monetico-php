<?php

use DansMaCulotte\Monetico\Payment\Receipt;
use PHPUnit\Framework\TestCase;

class PaymentReceiptTest extends TestCase
{
    public function testPaymentReceiptConstruct()
    {
        $receipt = new Receipt(true);

        $this->assertTrue($receipt instanceof Receipt);
    }

    public function testPaymentReceiptOuput()
    {
        $receipt = new Receipt(true);

        $this->assertTrue((string) $receipt === 'version=2\ncdr=0\n');

        $receipt = new Receipt(false);

        $this->assertTrue((string) $receipt === 'version=2\ncdr=1\n');
    }
}