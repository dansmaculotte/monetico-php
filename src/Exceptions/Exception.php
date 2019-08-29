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
        return new self("Security key is invalid, should be 40 characters long.");
    }


    /**
     * @param $name
     * @return Exception
     */
    public static function invalidDateTime($name): self
    {
        return new self("{$name} value is not a DateTime object");
    }

    /**
     * @param string $key
     * @param $value
     * @return Exception
     */
    public static function invalidValue(string $key, $value): self
    {
        return new self("{$key} is invalid: ${value}");
    }
}
