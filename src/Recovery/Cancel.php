<?php

namespace DansMaCulotte\Monetico\Recovery;
use DansMaCulotte\Monetico\Exceptions\CancelException;
use DansMaCulotte\Monetico\Exceptions\Exception;

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
        if (!is_a($this->datetime, 'DateTime')) {
            throw Exception::invalidDatetime();
        }

        if (!is_a($this->orderDatetime, 'DateTime')) {
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