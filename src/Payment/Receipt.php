<?php

namespace DansMaCulotte\Monetico\Payment;

class Receipt
{
    const OUPUT_FORMAT = "version=2\ncdr=%s\n";

    /** @var bool */
    public $status;

    /**
     * PaymentReceipt constructor.
     * @param bool $status
     */
    public function __construct($status)
    {
        $this->status = $status;
    }

    public function __toString()
    {
        return sprintf(self::OUPUT_FORMAT, $this->status ? 0 : 1);
    }
}