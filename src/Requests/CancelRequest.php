<?php

namespace DansMaCulotte\Monetico\Requests;

use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Exceptions\RecoveryException;
use DateTime;

class CancelRequest extends RecoveryRequest
{
    /**
     * Cancel constructor.
     * @param array $data
     * @throws Exception
     * @throws RecoveryException
     */
    public function __construct(array $data = [])
    {
        $data['amountLeft'] = 0;
        $data['amountToRecover'] = 0;

        parent::__construct($data);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function validate(): bool
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

        if (strlen($this->language) !== 2) {
            throw Exception::invalidLanguage($this->language);
        }

        return true;
    }
}
