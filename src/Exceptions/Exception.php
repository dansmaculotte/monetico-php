<?php

namespace DansMaCulotte\Monetico\Exceptions;

class Exception extends \Exception
{
    /**
     * @param string $eptCode
     *
     * @return Exception
     */
    public static function invalidEptCode($eptCode)
    {
        return new self("EPT code is invalid, should be 7 characters long: ${eptCode}");
    }

    /**
     * @return Exception
     */
    public static function invalidSecurityKey()
    {
        return new self("Security key is invalid, should be 40 characters long.");
    }

    /**
     * @return Exception
     */
    public static function invalidOrderDatetime()
    {
        return new self("date_commande value is not a DateTime object");
    }

    /**
     * @return Exception
     */
    public static function invalidDatetime()
    {
        return new self("date value is not a DateTime object");
    }

    /**
     * @return Exception
     */
    public static function invalidRecoveryDatetime()
    {
        return new self("datetime value is not a DateTime object");
    }

    /**
     * @param $invoiceType
     *
     * @return Exception
     */
    public static function invalidInvoiceType($invoiceType)
    {
        return new self("facture value is invalid: ${invoiceType}");
    }

    /**
     * @param string $language
     *
     * @return Exception
     */
    public static function invalidLanguage($language)
    {
        return new self("language value is invalid, should be 12 characters long maximum: ${language}");
    }

    /**
     * @param string $reference
     *
     * @return Exception
     */
    public static function invalidReference($reference)
    {
        return new self("reference value is invalid, should be 12 characters long maximum: ${reference}");
    }

    /**
     * @param string $key
     *
     * @return Exception
     */
    public static function missingResponseKey($key)
    {
        return new self("${key} is missing");
    }

}