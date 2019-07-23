<?php

namespace DansMaCulotte\Monetico\Resources;

class AddressShipping
{
    /** @var array */
    public $data = [];

    /**
     * Client constructor.
     *
     * @param string $addressLine1
     * @param string $city
     * @param string $postalCode
     * @param string $country
     * @param string|null $civility
     * @param string|null $name
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $address
     * @param string|null $addressLine2
     * @param string|null $addressLine3
     * @param string|null $stateOrProvince
     * @param string|null $countrySubdivision
     * @param string|null $email
     * @param string|null $phone
     * @param string|null $shipIndicator
     * @param string|null $deliveryTimeframe
     * @param string|null $firstUseDate
     * @param bool $matchBillingAddress
     */
    public function __construct( string $addressLine1,
                                 string $city,
                                 string $postalCode,
                                 string $country,
                                 string $civility = null,
                                 string $name = null,
                                 string $firstName = null,
                                 string $lastName = null,
                                 string $address = null,
                                 string $addressLine2 = null,
                                 string $addressLine3 = null,
                                 string $stateOrProvince = null,
                                 string $countrySubdivision = null,
                                 string $email = null,
                                 string $phone = null,
                                 string $shipIndicator = null,
                                 string $deliveryTimeframe = null,
                                 string $firstUseDate = null,
                                 bool $matchBillingAddress = false
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
            'address' => $address,
            'addressLine2' => $addressLine2,
            'addressLine3' => $addressLine3,
            'stateOrProvince' => $stateOrProvince,
            'countrySubdivision' => $countrySubdivision,
            'email' => $email,
            'phone' => $phone,
            'mobilePhone' => $shipIndicator,
            'homePhone' => $deliveryTimeframe,
            'workPhone' => $firstUseDate,
            'matchBillingAddress' => $matchBillingAddress,
        ];
    }
}
