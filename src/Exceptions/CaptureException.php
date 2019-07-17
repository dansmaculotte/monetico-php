<?php

namespace DansMaCulotte\Monetico\Exceptions;

class CaptureException extends \Exception
{
    /**
     * @param $invoiceType
     *
     * @return CaptureException
     */
    public static function invalidInvoiceType($invoiceType)
    {
        return new self("invoice type invalid: ${invoiceType}");
    }


    /**
     * @return CaptureException
     */
    public static function invalidDatetime()
    {
        return new self("datetime value is not a DateTime object");
    }

    /**
     * @return CaptureException
     */
    public static function invalidOrderDatetime()
    {
        return new self("orderDatetime value is not a DateTime object");
    }

    /**
     * @param string $reference
     *
     * @return CaptureException
     */
    public static function invalidReference($reference)
    {
        return new self("reference value is invalid, should be 12 characters long maximum: ${reference}");
    }

    /**
     * @param string $language
     *
     * @return CaptureException
     */
    public static function invalidLanguage($language)
    {
        return new self("language value is invalid, should be 12 characters long maximum: ${language}");
    }

    /**
     * @param $total
     * @param $toCapture
     * @param $captured
     * @param $left
     *
     * @return CaptureException
     */
    public static function invalidAmounts($total, $toCapture, $captured, $left)
    {
        return new self("amounts values are invalid, the sum of the amount to capture: ${toCapture}, the amount captured: ${captured} and the amount left: ${left} should be equal to the total amount: ${total}");
    }
}