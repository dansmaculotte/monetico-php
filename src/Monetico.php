<?php

namespace DansMaCulotte\Monetico;

use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Requests\AbstractRequest;
use DansMaCulotte\Monetico\Responses\AbstractResponse;

class Monetico
{
    /** @var string */
    const SERVICE_VERSION = '3.0';

    /** @var string */
    const MAIN_REQUEST_URL = 'https://p.monetico-services.com';

    /** @var string */
    const MISC_REQUEST_URL = 'https://payment-api.e-i.com';

    /** @var string|null */
    private $eptCode = null;

    /** @var string|null */
    private $securityKey = null;

    /** @var string|null */
    private $companyCode = null;

    /**
     * Construct method
     *
     * @param string $eptCode EPT code
     * @param string $securityKey Security key
     * @param string $companyCode Company code
     * @throws Exception
     */
    public function __construct(
        string $eptCode,
        string $securityKey,
        string $companyCode
    ) {
        if (strlen($eptCode) !== 7) {
            throw Exception::invalidEptCode($eptCode);
        }

        if (strlen($securityKey) !== 40) {
            throw Exception::invalidSecurityKey();
        }

        $this->eptCode = $eptCode;
        $this->securityKey = self::getUsableKey($securityKey);
        $this->companyCode = $companyCode;
    }

    /**
     * Transform security key for seal
     *
     * @param string $key
     * @return string
     */
    public static function getUsableKey(string $key): string
    {
        $hexStrKey = substr($key, 0, 38);
        $hexFinal = '' . substr($key, 38, 2) . '00';

        $cca0 = ord($hexFinal);

        if ($cca0 > 70 && $cca0 < 97) {
            $hexStrKey .= chr($cca0 - 23) . $hexFinal[1];
        } else {
            if ($hexFinal[1] === 'M') {
                $hexStrKey .= $hexFinal[0] . '0';
            } else {
                $hexStrKey .= substr($hexFinal, 0, 2);
            }
        }

        return pack('H*', $hexStrKey);
    }

    /**
     * Return array fields required on bank interface
     *
     * @param AbstractRequest $request
     * @return array
     */
    public function getFields(AbstractRequest $request): array
    {
        $fields = $request->fieldsToArray(
            $this->eptCode,
            $this->companyCode,
            self::SERVICE_VERSION
        );

        $seal = $request->generateSeal(
            $this->securityKey,
            $fields
        );

        $fields = $request->generateFields(
            $seal,
            $fields
        );

        return $fields;
    }

    /**
     * Validate seal from response
     *
     * @param AbstractResponse $response
     * @return bool
     */
    public function validate(AbstractResponse $response): bool
    {
        $seal = $response->validateSeal(
            $this->eptCode,
            $this->securityKey
        );

        return $seal;
    }
}
