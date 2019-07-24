<?php

namespace DansMaCulotte\Monetico\Resources;

class Client
{
    /** @var array */
    public $data = [];

    /**
     * Client constructor.
     *
     * @param string|null $civility
     * @param string|null $name
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $middleName
     * @param string|null $address
     * @param string|null $addressLine1
     * @param string|null $addressLine2
     * @param string|null $addressLine3
     * @param string|null $city
     * @param string|null $postalCode
     * @param string|null $country
     * @param string|null $stateOrProvince
     * @param string|null $countrySubdivision
     * @param string|null $email
     * @param string|null $birthLastName
     * @param string|null $birthCity
     * @param string|null $birthPostalCode
     * @param string|null $birthCountry
     * @param string|null $birthStateOrProvince
     * @param string|null $birthCountrySubdivision
     * @param string|null $birthdate
     * @param string|null $phone
     * @param string|null $nationalIDNumber
     * @param string|null $suspiciousAccountActivity
     * @param string|null $authenticationMethod
     * @param string|null $authenticationTimestamp
     * @param string|null $priorAuthenticationMethod
     * @param string|null $priorAuthenticationTimestamp
     * @param string|null $paymentMeanAge
     * @param string|null $lastYearTransactions
     * @param string|null $last24HoursTransactions
     * @param string|null $addCardNbLast24Hours
     * @param string|null $last6MonthsPurchase
     * @param string|null $lastPasswordChange
     * @param string|null $accountAge
     * @param string|null $lastAccountModification
     */
    public function __construct(
        string $civility = null,
        string $name = null,
        string $firstName = null,
        string $lastName = null,
        string $middleName = null,
        string $address = null,
        string $addressLine1 = null,
        string $addressLine2 = null,
        string $addressLine3 = null,
        string $city = null,
        string $postalCode = null,
        string $country  = null,
        string $stateOrProvince = null,
        string $countrySubdivision = null,
        string $email = null,
        string $birthLastName = null,
        string $birthCity = null,
        string $birthPostalCode = null,
        string $birthCountry = null,
        string $birthStateOrProvince = null,
        string $birthCountrySubdivision = null,
        string $birthdate = null,
        string $phone = null,
        string $nationalIDNumber = null,
        string $suspiciousAccountActivity = null,
        string $authenticationMethod = null,
        string $authenticationTimestamp = null,
        string $priorAuthenticationMethod = null,
        string $priorAuthenticationTimestamp = null,
        string $paymentMeanAge = null,
        string $lastYearTransactions = null,
        string $last24HoursTransactions = null,
        string $addCardNbLast24Hours = null,
        string $last6MonthsPurchase = null,
        string $lastPasswordChange = null,
        string $accountAge = null,
        string $lastAccountModification = null
    ) {
        $this->data = [
            'civility' => $civility,
            'name' => $name,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'middleName' => $middleName,
            'address' => $address,
            'addressLine1' => $addressLine1,
            'addressLine2' => $addressLine2,
            'addressLine3' => $addressLine3,
            'city' => $city,
            'postalCode' => $postalCode,
            'country' => $country,
            'stateOrProvince' => $stateOrProvince,
            'countrySubdivision' => $countrySubdivision,
            'email' => $email,
            'birthLastName' => $birthLastName,
            'birthCity' => $birthCity,
            'birthPostalCode' => $birthPostalCode,
            'birthCountry' => $birthCountry,
            'birthStateOrProvince' => $birthStateOrProvince,
            'birthCountrySubdivision' => $birthCountrySubdivision,
            'birthdate' => $birthdate,
            'phone' => $phone,
            'nationalIDNumber' => $nationalIDNumber,
            'suspiciousAccountActivity' => $suspiciousAccountActivity,
            'authenticationMethod' => $authenticationMethod,
            'authenticationTimestamp' => $authenticationTimestamp,
            'priorAuthenticationMethod' => $priorAuthenticationMethod,
            'priorAuthenticationTimestamp' => $priorAuthenticationTimestamp,
            'paymentMeanAge' => $paymentMeanAge,
            'lastYearTransactions' => $lastYearTransactions,
            'last24HoursTransactions' => $last24HoursTransactions,
            'addCardNbLast24Hours' => $addCardNbLast24Hours,
            'last6MonthsPurchase' => $last6MonthsPurchase,
            'lastPasswordChange' => $lastPasswordChange,
            'accountAge' => $accountAge,
            'lastAccountModification' => $lastAccountModification,
        ];
    }
}
