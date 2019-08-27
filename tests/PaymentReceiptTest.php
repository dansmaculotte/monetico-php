<?php

use DansMaCulotte\Monetico\Receipts\PaymentReceipt;
use PHPUnit\Framework\TestCase;

class PaymentReceiptTest extends TestCase
{
    public function testPaymentReceiptConstruct()
    {
        $receipt = new PaymentReceipt(true);

        $this->assertTrue($receipt instanceof PaymentReceipt);
    }

    public function testPaymentReceiptOuput()
    {
        $receipt = new PaymentReceipt(true);

        $this->assertTrue((string) $receipt === "version=2\ncdr=0\n");

        $receipt = new PaymentReceipt(false);

        $this->assertTrue((string) $receipt === "version=2\ncdr=1\n");
    }
}
