<?php

namespace DansMaCulotte\Monetico\Cancel;
use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Recovery\Recovery;
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
            throw Exception::invalidOrderDate();
        }

        if (strlen($this->reference) > 12) {
            throw Exception::invalidReference($this->reference);
        }

        if (strlen($this->language) != 2) {
            throw Exception::invalidLanguage($this->language);
        }
    }
}