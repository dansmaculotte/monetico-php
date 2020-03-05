<?php

namespace DansMaCulotte\Monetico\Receipts;

class PurchaseReceipt
{
    /** @var string */
    const OUTPUT_FORMAT = "version=2\ncdr=%s\n";

    /** @var bool */
    public $status;

    /**
     * PaymentReceipt constructor.
     * @param bool $status
     */
    public function __construct(bool $status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(self::OUTPUT_FORMAT, $this->status ? 0 : 1);
    }
}
