<?php

namespace DansMaCulotte\Monetico\Exceptions;

class RecoveryException extends \Exception
{
    /**
     * @param string $total
     * @param string $toRecover
     * @param string $recovered
     * @param string $left
     * @return RecoveryException
     */
    public static function invalidAmounts($total, $toRecover, $recovered, $left): self
    {
        return new self("amounts values are invalid, the sum of the amount to recover: ${toRecover}, the amount recovered: ${recovered} and the amount left: ${left} should be equal to the total amount: ${total}");
    }

    /**
     * @return RecoveryException
     */
    public static function invalidResponseDebitDate(): self
    {
        return new self('date_debit value is invalid');
    }

    /**
     * @return RecoveryException
     */
    public static function invalidResponseAuthorizationDate(): self
    {
        return new self('date_autorisation value is invalid');
    }
}
