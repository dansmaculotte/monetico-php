<?php

namespace DansMaCulotte\Monetico\Exceptions;


class CancelException extends \Exception
{
    /**
     * @param $toRecover
     * @param $left
     *
     * @return CancelException
     */
    public static function invalidAmounts($toRecover, $left)
    {
        return new self("amounts values are invalid, amount left: ${left} and amount to recover: ${toRecover} must be equal to 0");
    }
}