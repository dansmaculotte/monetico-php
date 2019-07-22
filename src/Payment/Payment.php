<?php

namespace DansMaCulotte\Monetico\Payment;

use DansMaCulotte\Monetico\BaseMethod;
use DansMaCulotte\Monetico\Exceptions\PaymentException;
use DansMaCulotte\Monetico\iMethod;

class Payment implements iMethod
{
    use BaseMethod;

    /** @var string */
    public $reference;

    /** @var string */
    public $description;

    /** @var string */
    public $language;

    /** @var string */
    public $email;

    /** @var float */
    public $amount;

    /** @var string */
    public $currency;

    /** @var \DateTime */
    public $dateTime;

    /** @var array */
    public $options;

    /** @var array */
    public $billingAddress;

    /** @var array */
    public $shippingAddress;

    /** @var array */
    public $shoppingCart;

    /** @var array */
    public $client;

    /** @var array */
    public $commitments;

    /** @var array */
    const PAYMENT_WAYS = array(
        '1euro',
        '3xcb',
        '4xcb',
        'fivory',
        'paypal'
    );

    /** @var array */
    const THREE_D_SECURE_CHALLENGES = array(
        'no_preference',
        'challenge_preferred',
        'challenge_mandated',
        'no_challenge_requested',
        'no_challenge_requested_strong_authentication',
        'no_challenge_requested_trusted_third_party',
        'no_challenge_requested_risk_analysis'
    );

    /** @var string */
    const DATETIME_FORMAT = 'd/m/Y:H:i:s';

    /**
     * InputPayload constructor.
     *
     * @param array $data
     * @param array $commitments
     * @param array $options
     * @throws PaymentException
     */
    public function __construct($data = array(), $commitments = array(), $options = array())
    {
        $this->reference = $data['reference'];
        $this->language = $data['language'];
        $this->dateTime = $data['datetime'];
        $this->description = $data['description'];
        $this->email = $data['email'];
        $this->amount = $data['amount'];
        $this->currency = $data['currency'];
        $this->options = $options;
        $this->commitments = $commitments;

        $this->validate();
    }

    /**
     * @throws PaymentException
     */
    public function validate()
    {
        if (strlen($this->reference) > 12) {
            throw PaymentException::invalidReference($this->reference);
        }

        if (strlen($this->language) != 2) {
            throw PaymentException::invalidLanguage($this->language);
        }

        if (!$this->dateTime instanceof DateTime) {
            throw PaymentException::invalidDatetime();
        }
    }

    /**
     * Define card alias in case of an express payment
     *
     * @param string $alias Alias card name
     */
    public function setCardAlias($alias)
    {
        $this->options['aliascb'] = $alias;
    }

    /**
     * Force submission of card informations in case of an express payment
     *
     * @param bool $value Enable or disable submission
     */
    public function setForceCard($value = true)
    {
        $this->options['forcesaisiecb'] = ($value) ? '1' : '0';
    }

    /**
     * Bypass 3DSecure check
     *
     * @param bool $value Enable or disable bypass
     */
    public function setDisable3DS($value = true)
    {
        $this->options['3dsdebrayable'] = ($value) ? '1' : '0';
    }

    /**
     * 3DSecure V2 Choice
     *
     * @param bool $choice
     * @throws PaymentException
     */
    public function setThreeDSecureChallenge($choice)
    {
        if (!in_array($choice, self::THREE_D_SECURE_CHALLENGES)) {
            throw PaymentException::invalidThreeDSecureChallenge($choice);
        }

        $this->options['threeDsecureChallenge'] = $choice;
    }


    /**
     * Change company sign label on payment interface
     *
     * @param string $label New sign label content
     */
    public function setSignLabel($label)
    {
        $this->options['libelleMonetique'] = $label;
    }

    /**
     * Set order address billing
     *
     * @param string $addressLine1
     * @param string $city
     * @param string $postalCode
     * @param string $country
     * @param string $civility
     * @param string $name
     * @param string $firstName
     * @param string $lastName
     * @param string $middleName
     * @param string $address
     * @param string $addressLine2
     * @param string $addressLine3
     * @param string $stateOrProvince
     * @param string $countrySubdivision
     * @param string $email
     * @param string $phone
     * @param string $mobilePhone
     * @param string $homePhone
     * @param string $workPhone
     */
    public function setAddressBilling(string $addressLine1,
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
                                      string $workPhone = null)
    {
        $this->billingAddress = [
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

    /**
     * Set order address shipping
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
     * @param bool|null $matchBillingAddress
     */
    public function setAddressShipping(string $addressLine1,
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
                                       bool $matchBillingAddress = null)
    {
        $this->shippingAddress = [
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

    public function setClient($civility = null,
                              $name = null,
                              $firstName = null,
                              $lastName = null,
                              $middleName = null,
                              $address = null,
                              $addressLine1 = null,
                              $addressLine2 = null,
                              $addressLine3 = null,
                              $city = null,
                              $postalCode = null,
                              $country  = null,
                              $stateOrProvince = null,
                              $countrySubdivision = null,
                              $email = null,
                              $birthLastName = null,
                              $birthCity = null,
                              $birthPostalCode = null,
                              $birthCountry = null,
                              $birthStateOrProvince = null,
                              $birthCountrySubdivision = null,
                              $birthdate = null,
                              $phone = null,
                              $nationalIDNumber = null,
                              $suspiciousAccountActivity = null,
                              $authenticationMethod = null,
                              $authenticationTimestamp = null,
                              $priorAuthenticationMethod = null,
                              $priorAuthenticationTimestamp = null,
                              $paymentMeanAge = null,
                              $lastYearTransactions = null,
                              $last24HoursTransactions = null,
                              $addCardNbLast24Hours = null,
                              $last6MonthsPurchase = null,
                              $lastPasswordChange = null,
                              $accountAge = null,
                              $lastAccountModification = null)
    {

        $this->client = [
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
            'country ' => $country ,
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

    /**
     * Disable ways of payment on payment interface
     *
     * @param array[string] $ways List of payment ways to disable
     */
    public function setDisabledPaymentWays($ways = array())
    {
        $_ways = array();

        foreach ($ways as $way) {
            if (in_array($way, self::PAYMENT_WAYS)) {
                array_push($_ways, $way);
            }
        }

        $this->options['desactivemoyenpaiement'] = join(',', $_ways);
    }

    /**
     * Get order context
     *
     * @return string
     */
    public function orderContextBase64()
    {
        $contextCommand = [
            'billing' => $this->billingAddress,
            'shipping' => $this->shippingAddress,
            'client' => $this->client,
        ];

        return base64_encode(json_encode($contextCommand));
    }

    /**
     * @param $eptCode
     * @param $companyCode
     * @param $version
     * @return array
     */
    private function baseFields($eptCode, $companyCode, $version) {
        return [
            'TPE' => $eptCode,
            'date' => $this->dateTime->format(self::DATETIME_FORMAT),
            'contexte_commande' => $this->orderContextBase64(),
            'lgue' => $this->language,
            'mail' => $this->email,
            'montant' => $this->amount . $this->currency,
            'reference' => $this->reference,
            'societe' => $companyCode,
            'texte-libre' => $this->description,
            'version' => $version
        ];
    }


    /**
     * @param $returnUrl
     * @param $successUrl
     * @param $errorUrl
     * @return array
     */
    private function urlFields($returnUrl, $successUrl, $errorUrl) {
        return [
            'url_retour' => $returnUrl,
            'url_retour_ok' => $successUrl . '?reference=' . $this->reference,
            'url_retour_err' => $errorUrl . '?reference=' . $this->reference,
        ];
    }

    /**
     * @return array
     */
    private function commitmentsFields()
    {
        $commitments = $this->commitments;
        $commitmentsCount = count($commitments);

        return [
            'dateech1' => ($commitmentsCount >= 1) ? $commitments[0]['date'] : '',
            'dateech2' => ($commitmentsCount >= 2) ? $commitments[1]['date'] : '',
            'dateech3' => ($commitmentsCount >= 3) ? $commitments[2]['date'] : '',
            'dateech4' => ($commitmentsCount >= 4) ? $commitments[3]['date'] : '',
            'montantech1' => ($commitmentsCount >= 1) ? $commitments[0]['amount'] . $this->currency : '',
            'montantech2' => ($commitmentsCount >= 2) ? $commitments[1]['amount'] . $this->currency : '',
            'montantech3' => ($commitmentsCount >= 3) ? $commitments[2]['amount'] . $this->currency : '',
            'montantech4' => ($commitmentsCount >= 4) ? $commitments[3]['amount'] . $this->currency : '',
            'nbrech' => ($commitmentsCount > 0) ? $commitmentsCount : '',
        ];
    }

    /**
     * @return array
     */
    private function optionsFields() {
        return [
            'ThreeDSecureChallenge' => (isset($this->options['threeDsecureChallenge'])) ? $this->options['threeDsecureChallenge'] : '',
            '3dsdebrayable' => (isset($this->options['3dsdebrayable'])) ? $this->options['3dsdebrayable'] : '',
            'aliascb' => (isset($this->options['aliascb'])) ? $this->options['aliascb'] : '',
            'desactivemoyenpaiement' => (isset($this->options['desactivemoyenpaiement'])) ? $this->options['desactivemoyenpaiement'] : '',
            'forcesaisiecb' => (isset($this->options['forcesaisiecb'])) ? $this->options['forcesaisiecb'] : '',
            'libelleMonetique' => (isset($this->options['libelleMonetique'])) ? $this->options['libelleMonetique'] : '',
        ];

    }

    public function fieldsToArray($eptCode, $version, $companyCode, $returnUrl, $successUrl, $errorUrl)
    {
        return array_merge(
            $this->baseFields($eptCode, $companyCode, $version),
            $this->optionsFields(),
            $this->commitmentsFields(),
            $this->urlFields($returnUrl, $successUrl, $errorUrl));
    }
}