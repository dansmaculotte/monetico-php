<?php

namespace DansMaCulotte\Monetico\Exceptions;

class AuthenticationException extends \Exception
{
    /**
     * @param $protocol
     * @return AuthenticationException
     */
    public static function invalidProtocol($protocol)
    {
        return new self("protocol value is invalid: ${protocol}");
    }

    /**
     * @param $status
     * @return AuthenticationException
     */
    public static function invalidStatus($status)
    {
        return new self("status value is invalid: ${status}");
    }

    /**
     * @param $version
     * @return AuthenticationException
     */
    public static function invalidVersion($version)
    {
        return new self("version value is invalid: ${version}");
    }

    /**
     * @param $liabilityShift
     * @return AuthenticationException
     */
    public static function invalidLiabilityShift($liabilityShift)
    {
        return new self("liabilityShift value is invalid: ${liabilityShift}");
    }

    /**
     * @param $VERes
     * @return AuthenticationException
     */
    public static function invalidVERes($VERes)
    {
        return new self("VERes value is invalid: ${VERes}");
    }

    /**
     * @param $PARes
     * @return AuthenticationException
     */
    public static function invalidPARes($PARes)
    {
        return new self("PARes value is invalid: ${PARes}");
    }

    /**
     * @param $ARes
     * @return AuthenticationException
     */
    public static function invalidARes($ARes)
    {
        return new self("ARes value is invalid: ${ARes}");
    }

    /**
     * @param $CRes
     * @return AuthenticationException
     */
    public static function invalidCRes($CRes)
    {
        return new self("CRes value is invalid: ${CRes}");
    }

    /**
     * @param $merchantPreference
     * @return AuthenticationException
     */
    public static function invalidMerchantPreference($merchantPreference)
    {
        return new self("merchantPreference value is invalid: ${merchantPreference}");
    }

    /**
     * @param string $DDDSStatus
     *
     * @return AuthenticationException
     */
    public static function invalidDDDSStatus($DDDSStatus)
    {
        return new self("status3DS value is invalid: ${DDDSStatus}");
    }

    /**
     * @param string $disablingReason
     *
     * @return AuthenticationException
     */
    public static function invalidDisablingReason($disablingReason)
    {
        return new self("disablingReason value is invalid: ${disablingReason}");
    }
}
