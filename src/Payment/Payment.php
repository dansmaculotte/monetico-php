<?php

namespace DansMaCulotte\Monetico\Payment;

use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Exceptions\PaymentException;
use DansMaCulotte\Monetico\Method;
use DansMaCulotte\Monetico\Resources\AddressBilling;
use DansMaCulotte\Monetico\Resources\AddressShipping;
use DansMaCulotte\Monetico\Resources\Client;
use DateTime;

class Payment extends Method
{
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

    /** @var AddressBilling */
    public $addressBilling;

    /** @var AddressShipping */
    public $addressShipping;

    /** @var Client */
    public $client;

    /** @var array */
    public $commitments;

    /** @var int */
    const MAC_COMMITMENTS = 4;

    /** @var array */
    const PAYMENT_WAYS = [
        '1euro',
        '3xcb',
        '4xcb',
        'fivory',
        'paypal'
    ];

    /** @var array */
    const THREE_D_SECURE_CHALLENGES = [
        'no_preference',
        'challenge_preferred',
        'challenge_mandated',
        'no_challenge_requested',
        'no_challenge_requested_strong_authentication',
        'no_challenge_requested_trusted_third_party',
        'no_challenge_requested_risk_analysis'
    ];

    /** @var string */
    const DATETIME_FORMAT = 'd/m/Y:H:i:s';

    /**
     * InputPayload constructor.
     *
     * @param array $data
     * @param array $commitments
     * @param array $options
     * @throws Exception
     */
    public function __construct($data = [], $commitments = [], $options = [])
    {
        $this->reference = $data['reference'];
        $this->language = $data['language'];
        $this->dateTime = $data['dateTime'];
        $this->description = $data['description'];
        $this->email = $data['email'];
        $this->amount = $data['amount'];
        $this->currency = $data['currency'];
        $this->options = $options;
        $this->commitments = $commitments;

        $this->validate();
    }

    /**
     * @throws Exception
     */
    public function validate()
    {
        if (strlen($this->reference) > 12) {
            throw Exception::invalidReference($this->reference);
        }

        if (strlen($this->language) != 2) {
            throw Exception::invalidLanguage($this->language);
        }

        if (!$this->dateTime instanceof DateTime) {
            throw Exception::invalidDatetime();
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


    public function setAddressBilling(AddressBilling $addressBilling)
    {
        $this->addressBilling = $addressBilling;
    }


    public function setAddressShipping(AddressShipping $addressShipping)
    {
        $this->addressShipping = $addressShipping;
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Disable ways of payment on payment interface
     *
     * @param array[string] $ways List of payment ways to disable
     */
    public function setDisabledPaymentWays($ways = [])
    {
        $_ways = [];

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
            'billing' => (isset($this->addressBilling)) ? $this->addressBilling->data : [],
            'shipping' => (isset($this->addressShipping)) ? $this->addressShipping->data : [],
            'client' => (isset($this->client)) ? $this->client->data : [],
        ];

        return base64_encode(json_encode($contextCommand));
    }

    /**
     * @param $eptCode
     * @param $companyCode
     * @param $version
     * @return array
     */
    private function baseFields($eptCode, $companyCode, $version)
    {
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
    private function urlFields($returnUrl, $successUrl, $errorUrl)
    {
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
        $commitmentsCount = count($this->commitments);
        $commitments = [
            'nbrech' => ($commitmentsCount > 0) ? $commitmentsCount : ''
        ];

        for ($i = 1; $i <= self::MAC_COMMITMENTS; $i++) {
            $commitments["dateech${i}"] = ($commitmentsCount >= $i) ? $this->commitments[$i - 1]['date'] : '';
            $commitments["montantech${i}"] = ($commitmentsCount >= $i) ? $this->commitments[$i - 1]['amount'] . $this->currency : '';
        }

        return $commitments;
    }

    /**
     * @return array
     */
    private function optionsFields()
    {
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
            $this->urlFields($returnUrl, $successUrl, $errorUrl)
        );
    }
}
