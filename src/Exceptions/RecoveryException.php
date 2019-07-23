<?php

namespace DansMaCulotte\Monetico\Exceptions;

class RecoveryException extends \Exception
{

    /**
     * @param $total
     * @param $toRecover
     * @param $recovered
     * @param $left
     *
     * @return RecoveryException
     */
    public static function invalidAmounts($total, $toRecover, $recovered, $left)
    {
        return new self("amounts values are invalid, the sum of the amount to recover: ${toRecover}, the amount recovered: ${recovered} and the amount left: ${left} should be equal to the total amount: ${total}");
    }

    /**
     * @return RecoveryException
     */
    public static function invalidResponseDebitDatetime()
    {
        return new self("date_debit value is invalid");
    }

}