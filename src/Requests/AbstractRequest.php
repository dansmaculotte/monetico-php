<?php

namespace DansMaCulotte\Monetico\Requests;

use DansMaCulotte\Monetico\Monetico;

abstract class AbstractRequest
{
    /** @var string */
    const REQUEST_URL = Monetico::MAIN_REQUEST_URL;

    /**
     * @param string $securityKey
     * @param array $fields
     * @return string
     */
    public function generateSeal(string $securityKey, array $fields): string
    {
        ksort($fields);

        $query = http_build_query($fields, null, '*');
        $query = urldecode($query);

        return strtoupper(hash_hmac(
            'sha1',
            $query,
            $securityKey
        ));
    }

    /**
     * @param string $seal
     * @param array $fields
     * @return array
     */
    public function generateFields(string $seal, array $fields): array
    {
        return array_merge(
            $fields,
            ['MAC' => $seal]
        );
    }

    /**
     * @return string
     */
    protected static function getRequestUrl(): string
    {
        return self::REQUEST_URL;
    }

    /**
     * @return string
     */
    abstract protected static function getRequestUri(): string;

    /**
     * @param bool $testMode
     * @return string
     */
    public static function getUrl(bool $testMode = false): string
    {
        $requestUrl = self::getRequestUrl();
        if ($testMode) {
            $requestUrl .= '/test';
        }

        return $requestUrl . '/' . static::getRequestUri();
    }

    /**
     * @return bool
     */
    abstract public function validate(): bool;

    /**
     * @param string $eptCode
     * @param string $companyCode
     * @param string $version
     * @return array
     */
    abstract public function fieldsToArray(string $eptCode, string $companyCode, string $version): array;
}
