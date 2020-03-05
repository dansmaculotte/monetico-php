<?php

namespace DansMaCulotte\Monetico\Exceptions;

class Exception extends \Exception
{
    /**
     * @param string $eptCode
     * @return Exception
     */
    public static function invalidEptCode(string $eptCode): self
    {
        return new self("EPT code is invalid, should be 7 characters long: ${eptCode}");
    }

    /**
     * @return Exception
     */
    public static function invalidSecurityKey(): self
    {
        return new self('Security key is invalid, should be 40 characters long.');
    }


    /**
     * @return Exception
     */
    public static function invalidDatetime(): self
    {
        return new self('dateTime value is not a DateTime object');
    }

    /**
     * @return Exception
     */
    public static function invalidRecoveryDate(): self
    {
        return new self('recoveryDate value is not a DateTime object');
    }

    /**
     * @param string $invoiceType
     * @return Exception
     */
    public static function invalidResponseInvoiceType(string $invoiceType): self
    {
        return new self("type_facture value is invalid: ${invoiceType}");
    }

    /**
     * @param string $invoiceType
     * @return Exception
     */
    public static function invalidInvoiceType(string $invoiceType): self
    {
        return new self("invoiceType value is invalid: ${invoiceType}");
    }

    /**
     * @param string $language
     * @return Exception
     */
    public static function invalidLanguage(string $language): self
    {
        return new self("language value is invalid, should be 12 characters long maximum: ${language}");
    }

    /**
     * @param string $reference
     * @return Exception
     */
    public static function invalidReference(string $reference): self
    {
        return new self("reference value is invalid, should be 50 characters long maximum: ${reference}");
    }

    /**
     * @param string $key
     * @return Exception
     */
    public static function missingResponseKey(string $key): self
    {
        return new self("${key} is missing");
    }

    /**
     *
     * @return Exception
     */
    public static function invalidResponseDateTime(): self
    {
        return new self('date value is invalid');
    }

    /**
     * @param string $fileNumber
     * @return Exception
     */
    public static function invalidResponseFileNumber($fileNumber): self
    {
        return new self("numero_dossier value is invalid: ${fileNumber}");
    }

    /**
     * @return Exception
     */
    public static function invalidOrderDate(): self
    {
        return new self('orderDate value is not a DateTime object');
    }

    /**
     * @param string $name
     * @return Exception
     */
    public static function invalidResourceParameter(string $name): self
    {
        return new self("resource parameter is invalid: {$name}");
    }
}
