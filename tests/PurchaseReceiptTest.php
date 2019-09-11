<?php

use DansMaCulotte\Monetico\Receipts\PurchaseReceipt;
use PHPUnit\Framework\TestCase;

class PurchaseReceiptTest extends TestCase
{
    public function testPaymentReceiptConstruct()
    {
        $receipt = new PurchaseReceipt(true);

        $this->assertTrue($receipt instanceof PurchaseReceipt);
    }

    public function testPaymentReceiptOuput()
    {
        $receipt = new PurchaseReceipt(true);

        $this->assertTrue((string) $receipt === "version=2\ncdr=0\n");

        $receipt = new PurchaseReceipt(false);

        $this->assertTrue((string) $receipt === "version=2\ncdr=1\n");
    }
}
