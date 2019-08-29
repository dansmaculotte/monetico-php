<?php

namespace DansMaCulotte\Monetico\Requests;

use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Monetico;

abstract class AbstractRequest
{
    /** @var string */
    const DATE_FORMAT = 'd/m/Y';

    /** @var string */
    const DATETIME_FORMAT = 'd/m/Y:H:i:s';

    /** @var string */
    const AMOUNT_REGEX = '/[0-9]+(\.[0-9]{1,2})?[A-Z]{3}/'; // ISO4217

    /** @var string */
    const REQUEST_URL = Monetico::MAIN_REQUEST_URL;

    /** @var array */
    protected $payload = [];

    /** @var array */
    protected $requiredKeys = [
        'dateTime' => [
            'field' => 'date',
            'validation' => 'datetime',
            'format' => self::DATETIME_FORMAT
        ],
        'orderDateTime' => [
            'field' => 'date_commande',
            'validation' => 'datetime',
            'format' => self::DATE_FORMAT
        ],
        'totalAmount' => [
            'field' => 'montant',
            'regex' => self::AMOUNT_REGEX,
        ],
        'captureAmount' => [
            'field' => 'montant_a_capturer',
            'regex' => self::AMOUNT_REGEX,
        ],
        'capturedAmount' => [
            'field' => 'montant_deja_capture',
            'regex' => self::AMOUNT_REGEX,
        ],
        'remainingAmount' => [
            'field' => 'montant_restant',
            'regex' => self::AMOUNT_REGEX,
        ],
        'reference' => [
            'field' => 'reference',
            'regex' => '/[a-zA-Z0-9]{1,50}/', // ISO4217
        ],
        'language' => [
            'field' => 'lgue',
            'regex' => '/[A-Z]{2}/', // DE EN ES FR IT JA NL PT SV
        ],
    ];

    /** @var array */
    protected $optionalKeys = [
        'stopRecurring' => [
            'field' => 'stoprecurrence',
            'default' => 'oui',
        ],
        'preAuthReference' => [
            'field' => 'numero_dossier',
            'regex' => '/[a-zA-Z0-9]{12}/',
        ],
        'invoiceType' => [
            'field' => 'facture',
            'values' => [
                'preauto',
                'noshow',
            ],
        ],
        'bankCall' => [
            'field' => 'phonie',
            'default' => 'oui',
        ],
    ];

    public function __construct(
        string $dateTime,
        string $orderDateTime,
        string $totalAmount,
        string $captureAmount,
        string $capturedAmount,
        string $remainingAmount,
        string $reference,
        string $language
    ) {
        $this->payload['dateTime'] = $dateTime;
        $this->payload['orderDateTime'] = $orderDateTime;
        $this->payload['totalAmount'] = $totalAmount;
        $this->payload['captureAmount'] = $captureAmount;
        $this->payload['capturedAmount'] = $capturedAmount;
        $this->payload['remainingAmount'] = $remainingAmount;
        $this->payload['reference'] = $reference;
        $this->payload['language'] = $language;

        $this->validateKeys($this->payload, $this->requiredKeys);
    }

    /**
     * @param array $data
     * @param array $rules
     * @throws Exception
     */
    private function validateKeys(array $data, array $rules)
    {
        foreach ($data as $key => $value) {
            $rule = $rules[$key];
            $this->validateKey($rule, $key, $value);
        }
    }

    /**
     * @param array $rule
     * @param string $key
     * @param string $value
     * @throws Exception
     */
    private function validateKey(array $rule, string $key, string $value)
    {
        if (isset($rule['validation'])) {
            switch ($rule['validation']) {
                case 'datetime':
                    $this->assertDateTimeObject($key, $value);
                    break;
            }
            $this->payload[$rule['field']] = $value;
        } else if (isset($rule['regex'])) {
            $this->assertRegexMatch($rule['regex'], $key, $value);
        } else if (isset($rule['values'])) {
            if (in_array($value, $rule['values'])) {
                $this->payload[$rule['field']] = $value;
            }
        } else if (isset($rule['value'])) {
            $this->payload[$rule['field']] = $rule['value'];
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @throws Exception
     */
    private function assertDateTimeObject($key, $value): void
    {
        if ($value instanceof \DateTime === false) {
            throw Exception::invalidDateTime($key);
        }
    }

    /**
     * @param string $pattern
     * @param string $key
     * @param string $value
     * @throws Exception
     */
    private function assertRegexMatch(string $pattern, string $key, string $value): void
    {
        $result = preg_match_all($pattern, $value, $matches, PREG_SET_ORDER, 0);

        if ($result === false || $result !== 1) {
            throw Exception::invalidValue($key, $value);
        }
    }

    /**
     * @param string $securityKey
     * @return string
     */
    public function generateSeal(string $securityKey): string
    {
        ksort($this->payload);

        $query = http_build_query($this->payload, null, '*');
        $query = urldecode($query);

        return strtoupper(hash_hmac(
            'sha1',
            $query,
            $securityKey
        ));
    }

    /**
     * @param string $seal
     * @return array
     */
    public function getPayload(string $seal): array
    {
        return array_merge(
            $this->payload,
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
     * @param string $eptCode
     * @param string $companyCode
     * @param string $version
     * @return array
     */
    abstract public function toArray(string $eptCode, string $companyCode, string $version): array;
}
