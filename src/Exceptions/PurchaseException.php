<?php

namespace DansMaCulotte\Monetico\Exceptions;

class PurchaseException extends \Exception
{
    /**
     * @param string $ThreeDSecureChallenge
     * @return PurchaseException
     */
    public static function invalidThreeDSecureChallenge(string $ThreeDSecureChallenge): self
    {
        return new self("ThreeDSecureChallenge value is invalid: ${ThreeDSecureChallenge}");
    }

    /**
     * @param string $returnCode
     * @return PurchaseException
     */
    public static function invalidResponseReturnCode(string $returnCode): self
    {
        return new self("code-retour value is invalid: ${returnCode}");
    }

    /**
     * @param string $status
     * @return PurchaseException
     */
    public static function invalidResponseCardVerificationStatus(string $status): self
    {
        return new self("cvx value is invalid: ${status}");
    }

    /**
     * @param string $brand
     * @return PurchaseException
     */
    public static function invalidResponseCardBrand(string $brand): self
    {
        return new self("brand value is invalid: ${brand}");
    }

    /**
     * @param string $rejectReason
     * @return PurchaseException
     */
    public static function invalidResponseRejectReason(string $rejectReason): self
    {
        return new self("motifrefus value is invalid: ${rejectReason}");
    }

    /**
     * @param string $paymentMethod
     *
     * @return PurchaseException
     */
    public static function invalidResponsePaymentMethod(string $paymentMethod): self
    {
        return new self("modepaiement value is invalid: ${paymentMethod}");
    }

    /**
     * @param string $filteredReason
     *
     * @return PurchaseException
     */
    public static function invalidResponseFilteredReason(string $filteredReason): self
    {
        return new self("filtragecause value is invalid: ${filteredReason}");
    }

    /**
     * @param string $protocole
     *
     * @return PurchaseException
     */
    public static function invalidProtocole(string $protocole): self
    {
        return new self("protocole value is invalid: ${protocole}");
    }
}
