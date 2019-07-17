<?php

namespace DansMaCulotte\Monetico\Exceptions;

class RecoveryException extends \Exception
{
    /**
     * @param $invoiceType
     *
     * @return RecoveryException
     */
    public static function invalidInvoiceType($invoiceType)
    {
        return new self("invoice type invalid: ${invoiceType}");
    }


    /**
     * @return RecoveryException
     */
    public static function invalidDatetime()
    {
        return new self("datetime value is not a DateTime object");
    }

    /**
     * @return RecoveryException
     */
    public static function invalidOrderDatetime()
    {
        return new self("orderDatetime value is not a DateTime object");
    }

    /**
     * @param string $reference
     *
     * @return RecoveryException
     */
    public static function invalidReference($reference)
    {
        return new self("reference value is invalid, should be 12 characters long maximum: ${reference}");
    }

    /**
     * @param string $language
     *
     * @return RecoveryException
     */
    public static function invalidLanguage($language)
    {
        return new self("language value is invalid, should be 12 characters long maximum: ${language}");
    }

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
}