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
     * @param $ThreeDSecureChallenge
     * @return PaymentException
     */
    public static function invalidThreeDSecureChallenge($ThreeDSecureChallenge)
    {
        return new self("ThreeDSecureChallenge value is invalid: ${ThreeDSecureChallenge}");
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
     * @param string $returnCode
     *
     * @return PaymentException
     */
    public static function invalidResponseReturnCode($returnCode)
    {
        return new self("code-retour value is invalid: ${returnCode}");
    }

    /**
     * @param string $status
     *
     * @return PaymentException
     */
    public static function invalidResponseCardVerificationStatus($status)
    {
        return new self("cvx value is invalid: ${status}");
    }

    /**
     * @param string $brand
     *
     * @return PaymentException
     */
    public static function invalidResponseCardBrand($brand)
    {
        return new self("brand value is invalid: ${brand}");
    }


    /**
     * @param string $rejectReason
     *
     * @return PaymentException
     */
    public static function invalidResponseRejectReason($rejectReason)
    {
        return new self("motifrefus value is invalid: ${rejectReason}");
    }

    /**
     * @param string $paymentMethod
     *
     * @return PaymentException
     */
    public static function invalidResponsePaymentMethod($paymentMethod)
    {
        return new self("modepaiement value is invalid: ${paymentMethod}");
    }

    /**
     * @param string $filteredReason
     *
     * @return PaymentException
     */
    public static function invalidResponseFilteredReason($filteredReason)
    {
        return new self("filtragecause value is invalid: ${filteredReason}");
    }
}