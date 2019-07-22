<?php

namespace DansMaCulotte\Monetico\Recovery;
use DansMaCulotte\Monetico\Exceptions\CancelException;
use DansMaCulotte\Monetico\Exceptions\Exception;
use DateTime;

class Cancel extends Recovery
{
    public function __construct(array $data = array())
    {
        $data['amountLeft'] = 0;
        $data['amountToRecover'] = 0;
        parent::__construct($data);
    }

    /**
     * @throws Exception
     */
    public function validate()
    {
        if (!$this->dateTime instanceof DateTime) {
            throw Exception::invalidDatetime();
        }

        if (!$this->orderDate instanceof DateTime) {
            throw Exception::invalidOrderDatetime();
        }

        if (strlen($this->reference) > 12) {
            throw Exception::invalidReference($this->reference);
        }

        if (strlen($this->language) != 2) {
            throw Exception::invalidLanguage($this->language);
        }
    }
}