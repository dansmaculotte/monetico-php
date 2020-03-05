<?php

namespace DansMaCulotte\Monetico\Exceptions;

class AuthenticationException extends \Exception
{
    /**
     * @param string $protocol
     * @return AuthenticationException
     */
    public static function invalidProtocol(string $protocol): self
    {
        return new self("protocol value is invalid: ${protocol}");
    }

    /**
     * @param string $status
     * @return AuthenticationException
     */
    public static function invalidStatus(string $status): self
    {
        return new self("status value is invalid: ${status}");
    }

    /**
     * @param string $version
     * @return AuthenticationException
     */
    public static function invalidVersion(string $version): self
    {
        return new self("version value is invalid: ${version}");
    }

    /**
     * @param string $liabilityShift
     * @return AuthenticationException
     */
    public static function invalidLiabilityShift(string $liabilityShift): self
    {
        return new self("liabilityShift value is invalid: ${liabilityShift}");
    }

    /**
     * @param string $VERes
     * @return AuthenticationException
     */
    public static function invalidVERes(string $VERes): self
    {
        return new self("VERes value is invalid: ${VERes}");
    }

    /**
     * @param string $PARes
     * @return AuthenticationException
     */
    public static function invalidPARes(string $PARes): self
    {
        return new self("PARes value is invalid: ${PARes}");
    }

    /**
     * @param string $ARes
     * @return AuthenticationException
     */
    public static function invalidARes(string $ARes): self
    {
        return new self("ARes value is invalid: ${ARes}");
    }

    /**
     * @param string $CRes
     * @return AuthenticationException
     */
    public static function invalidCRes(string $CRes): self
    {
        return new self("CRes value is invalid: ${CRes}");
    }

    /**
     * @param string $merchantPreference
     * @return AuthenticationException
     */
    public static function invalidMerchantPreference(string $merchantPreference): self
    {
        return new self("merchantPreference value is invalid: ${merchantPreference}");
    }

    /**
     * @param string $DDDSStatus
     * @return AuthenticationException
     */
    public static function invalidDDDSStatus(string $DDDSStatus): self
    {
        return new self("status3DS value is invalid: ${DDDSStatus}");
    }

    /**
     * @param string $disablingReason
     * @return AuthenticationException
     */
    public static function invalidDisablingReason(string $disablingReason): self
    {
        return new self("disablingReason value is invalid: ${disablingReason}");
    }
}
