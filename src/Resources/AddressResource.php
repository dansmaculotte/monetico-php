<?php

namespace DansMaCulotte\Monetico\Resources;

class AddressResource
{
    /** @var array */
    public $data = [];

    /**
     * Client constructor.
     *
     * @param string $addressLine
     * @param string $city
     * @param string $postalCode
     * @param string $country
     */
    public function __construct(
        string $addressLine,
        string $city,
        string $postalCode,
        string $country
    ) {
        $this->data = [
            'addressLine1' => $addressLine,
            'city' => $city,
            'postalCode' => $postalCode,
            'country' => $country,
        ];
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setOptionalField(string $name, string $value): void
    {
        $this->data[$name] = $value;
    }
}
