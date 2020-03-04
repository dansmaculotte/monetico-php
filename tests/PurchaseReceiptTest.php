<?php

use DansMaCulotte\Monetico\Receipts\PurchaseReceipt;
use PHPUnit\Framework\TestCase;

class PurchaseReceiptTest extends TestCase
{
    public function testPaymentReceiptConstruct()
    {
        $receipt = new PurchaseReceipt(true);

        $this->assertInstanceOf(PurchaseReceipt::class, $receipt);
    }

    public function testPaymentReceiptOuput()
    {
        $receipt = new PurchaseReceipt(true);

        $this->assertSame((string) $receipt, "version=2\ncdr=0\n");

        $receipt = new PurchaseReceipt(false);

        $this->assertSame((string) $receipt, "version=2\ncdr=1\n");
    }
}
