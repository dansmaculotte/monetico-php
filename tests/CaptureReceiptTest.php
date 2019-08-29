<?php

use DansMaCulotte\Monetico\Receipts\CaptureReceipt;
use PHPUnit\Framework\TestCase;

class CaptureReceiptTest extends TestCase
{
    public function testPaymentReceiptConstruct()
    {
        $receipt = new CaptureReceipt(true);

        $this->assertTrue($receipt instanceof CaptureReceipt);
    }

    public function testPaymentReceiptOuput()
    {
        $receipt = new CaptureReceipt(true);

        $this->assertTrue((string) $receipt === "version=2\ncdr=0\n");

        $receipt = new CaptureReceipt(false);

        $this->assertTrue((string) $receipt === "version=2\ncdr=1\n");
    }
}
