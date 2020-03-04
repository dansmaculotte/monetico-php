<?php

namespace DansMaCulotte\Monetico\Responses;

use DansMaCulotte\Monetico\Exceptions\Exception;

abstract class AbstractResponse
{
    /** @var array */
    const REQUIRED_KEYS = [
        'cdr' => 'returnCode',
        'lib' => 'description',
        'version' => 'version',
        'reference' => 'reference',
    ];

    /** @var string */
    public $returnCode;

    /** @var string */
    public $description;

    /** @var string */
    public $version;

    /** @var string */
    public $reference;

    /**
     * AbstractResponse constructor.
     * @param array $data
     * @throws Exception
     */
    public function __construct(array $data)
    {
        $this->validateRequiredKeys($data);
        $this->setDataRequiredKeys($data);
    }

    /**
     * @return array
     */
    protected function getRequiredKeys(): array
    {
        return self::REQUIRED_KEYS;
    }

    /**
     * @param array $data
     * @throws Exception
     */
    private function validateRequiredKeys(array $data): void
    {
        $keys = $this->getRequiredKeys();
        foreach ($keys as $key => $value) {
            if (!array_key_exists($key, $data)) {
                throw Exception::missingResponseKey($key);
            }
        }
    }

    /**
     * @param array $data
     */
    private function setDataRequiredKeys(array $data)
    {
        $keys = $this->getRequiredKeys();

        foreach ($data as $key => $value) {
            if (isset($keys[$key])) {
                $method = $keys[$key];
                $this->$method = $value;
            }
        }
    }

    /**
     * @param string $eptCode
     * @param string $companyCode
     * @return bool
     */
    public function validateSeal(string $eptCode, string $companyCode): bool
    {
        return false;
    }
}
