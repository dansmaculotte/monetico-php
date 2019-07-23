<?php

namespace DansMaCulotte\Monetico\Resources;

class AddressBilling
{
    /** @var array */
    public $data = [];

    /**
     * Client constructor.
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->setData(...$data);
    }

    /**
     * @param string $addressLine1
     * @param string $city
     * @param string $postalCode
     * @param string $country
     * @param string|null $civility
     * @param string|null $name
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $middleName
     * @param string|null $address
     * @param string|null $addressLine2
     * @param string|null $addressLine3
     * @param string|null $stateOrProvince
     * @param string|null $countrySubdivision
     * @param string|null $email
     * @param string|null $phone
     * @param string|null $mobilePhone
     * @param string|null $homePhone
     * @param string|null $workPhone
     */
    public function setData(
        string $addressLine1,
        string $city,
        string $postalCode,
        string $country,
        string $civility = null,
        string $name = null,
        string $firstName = null,
        string $lastName = null,
        string $middleName = null,
        string $address = null,
        string $addressLine2 = null,
        string $addressLine3 = null,
        string $stateOrProvince = null,
        string $countrySubdivision = null,
        string $email = null,
        string $phone = null,
        string $mobilePhone = null,
        string $homePhone = null,
        string $workPhone = null
    ) {
        $this->data = [
            'addressLine1' => $addressLine1,
            'city' => $city,
            'postalCode' => $postalCode,
            'country' => $country,
            'civility' => $civility,
            'name' => $name,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'middleName' => $middleName,
            'address' => $address,
            'addressLine2' => $addressLine2,
            'addressLine3' => $addressLine3,
            'stateOrProvince' => $stateOrProvince,
            'countrySubdivision' => $countrySubdivision,
            'email' => $email,
            'phone' => $phone,
            'mobilePhone' => $mobilePhone,
            'homePhone' => $homePhone,
            'workPhone' => $workPhone,
        ];
    }
}
