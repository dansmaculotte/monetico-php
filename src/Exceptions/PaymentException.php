<?php

namespace DansMaCulotte\Monetico\Exceptions;

class PaymentException extends \Exception
{
    /**
     * @param string $reference
     *
     * @return PaymentException
     */
    public static function invalidReference($reference)
    {
        return new self("reference value is invalid, should be 12 characters long maximum: ${reference}");
    }

    /**
     * @param string $language
     *
     * @return PaymentException
     */
    public static function invalidLanguage($language)
    {
        return new self("language value is invalid, should be 12 characters long maximum: ${language}");
    }

    /**
     * @return PaymentException
     */
    public static function invalidDatetime()
    {
        return new self("datetime value is not a DateTime object");
    }

    /**
     * @param string $key
     *
     * @return PaymentException
     */
    public static function missingResponseKey($key)
    {
        return new self("${key} is missing");
    }

    /**
     * @param string $returnCode
     *
     * @return PaymentException
     */
    public static function invalidReturnCode($returnCode)
    {
        return new self("code-retour value is invalid: ${returnCode}");
    }

    /**
     * @param string $status
     *
     * @return PaymentException
     */
    public static function invalidCardVerificationStatus($status)
    {
        return new self("cvx value is invalid: ${status}");
    }

    /**
     * @param string $brand
     *
     * @return PaymentException
     */
    public static function invalidCardBrand($brand)
    {
        return new self("brand value is invalid: ${brand}");
    }

    /**
     * @param string $DDDSStatus
     *
     * @return PaymentException
     */
    public static function invalidDDDSStatus($DDDSStatus)
    {
        return new self("status3ds value is invalid: ${DDDSStatus}");
    }

    /**
     * @param string $rejectReason
     *
     * @return PaymentException
     */
    public static function invalidRejectReason($rejectReason)
    {
        return new self("motifrefus value is invalid: ${rejectReason}");
    }

    /**
     * @param string $paymentMethod
     *
     * @return PaymentException
     */
    public static function invalidPaymentMethod($paymentMethod)
    {
        return new self("modepaiement value is invalid: ${paymentMethod}");
    }

    /**
     * @param string $filteredReason
     *
     * @return PaymentException
     */
    public static function invalidFilteredReason($filteredReason)
    {
        return new self("filtragecause value is invalid: ${filteredReason}");
    }

    /**
     * @param $ThreeDSecureChallenge
     * @return PaymentException
     */
    public static function invalidThreeDSecureChallenge($ThreeDSecureChallenge)
    {
        return new self("ThreeDSecureChallenge value is invalid: ${ThreeDSecureChallenge}");
    }
}