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

}