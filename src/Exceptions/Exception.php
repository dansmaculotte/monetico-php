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
    public static function invalidDatetime()
    {
        return new self("dateTime value is not a DateTime object");
    }

    /**
     * @return Exception
     */
    public static function invalidRecoveryDate()
    {
        return new self("recoveryDate value is not a DateTime object");
    }

    /**
     * @param $invoiceType
     *
     * @return Exception
     */
    public static function invalidResponseInvoiceType($invoiceType)
    {
        return new self("type_facture value is invalid: ${invoiceType}");
    }

    /**
     * @param $invoiceType
     *
     * @return Exception
     */
    public static function invalidInvoiceType($invoiceType)
    {
        return new self("invoiceType value is invalid: ${invoiceType}");
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

    /**
     *
     * @return Exception
     */
    public static function invalidResponseDateTime()
    {
        return new self("date value is invalid");
    }

    /**
     * @param $fileNumber
     * @return Exception
     */
    public static function invalidResponseFileNumber($fileNumber)
    {
        return new self("numero_dossier value is invalid: ${fileNumber}");
    }

    /**
     * @return Exception
     */
    public static function invalidOrderDate()
    {
        return new self("orderDate value is not a DateTime object");
    }
}
