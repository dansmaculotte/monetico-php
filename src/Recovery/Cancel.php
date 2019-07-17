<?php

namespace DansMaCulotte\Monetico\Recovery;
use DansMaCulotte\Monetico\Exceptions\CancelException;

class Cancel extends Recovery
{
    public function __construct(array $data = array())
    {
        $data['amountLeft'] = 0;
        $data['amountToRecover'] = 0;
        parent::__construct($data);
    }

    /**
     * @throws CancelException
     */
    public function validateAmounts()
    {
        if ($this->amountLeft !== 0 || $this->amountToRecover !== 0) {
            throw CancelException::invalidAmounts($this->amountToRecover, $this->amountLeft);
        }
    }
}