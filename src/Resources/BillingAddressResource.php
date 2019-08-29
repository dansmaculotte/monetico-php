<?php

namespace DansMaCulotte\Monetico\Resources;

use DansMaCulotte\Monetico\Exceptions\Exception;

class BillingAddressResource extends Ressource
{
    /** @var array */
    protected $keys = [
        'civility',
        'name',
        'firstName',
        'lastName',
        'middleName',
        'address',
        'addressLine1',
        'addressLine2',
        'addressLine3',
        'city',
        'postalCode',
        'country',
        'stateOrProvince',
        'countrySubdivision',
        'email',
        'phone',
        'mobilePhone',
        'homePhone',
        'workPhone',
    ];

    /**
     * Client constructor.
     *
     * @param string $addressLine
     * @param string $city
     * @param string $postalCode
     * @param string $country
     * @throws Exception
     */
    public function __construct(string $addressLine, string $city, string $postalCode, string $country)
    {
        parent::__construct([
            'addressLine1' => $addressLine,
            'city' => $city,
            'postalCode' => $postalCode,
            'country' => $country,
        ]);
    }
}
