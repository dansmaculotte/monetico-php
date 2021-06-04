<?php

namespace DansMaCulotte\Monetico\Requests;

use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Exceptions\PurchaseException;
use DansMaCulotte\Monetico\Resources\BillingAddressResource;
use DansMaCulotte\Monetico\Resources\CartResource;
use DansMaCulotte\Monetico\Resources\ClientResource;
use DansMaCulotte\Monetico\Resources\ShippingAddressResource;
use DateTime;

class PurchaseRequest extends AbstractRequest
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

    /** @var */
    public $successUrl;

    /** @var */
    public $errorUrl;

    /** @var array */
    public $options;

    /** @var BillingAddressResource */
    public $billingAddress;

    /** @var ShippingAddressResource */
    public $shippingAddress;

    /** @var ClientResource */
    public $client;

    /** @var CartResource */
    public $cart;

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

    /** @var array */
    const PROTOCOLES = [
        '1euro',
        '3xcb',
        '4xcb',
        'paypal',
        'lyfpay'
    ];

    /** @var string */
    const DATETIME_FORMAT = 'd/m/Y:H:i:s';

    /** @var string */
    const REQUEST_URI = 'paiement.cgi';

    /**
     * InputPayload constructor.
     *
     * @param array $data
     * @param array $commitments
     * @param array $options
     * @throws Exception
     */
    public function __construct(array $data = [], array $commitments = [], array $options = [])
    {
        $this->reference = $data['reference'];
        $this->language = strtoupper($data['language']);
        $this->dateTime = $data['dateTime'];
        $this->description = $data['description'];
        $this->email = $data['email'];
        $this->amount = $data['amount'];
        $this->currency = $data['currency'];
        $this->successUrl = $data['successUrl'];
        $this->errorUrl = $data['errorUrl'];
        $this->options = $options;
        $this->commitments = $commitments;

        $this->validate();
    }

    /**
     * @throws Exception
     */
    public function validate(): bool
    {
        if (strlen($this->reference) > 50) {
            throw Exception::invalidReference($this->reference);
        }

        if (strlen($this->language) !== 2) {
            throw Exception::invalidLanguage($this->language);
        }

        if (!$this->dateTime instanceof DateTime) {
            throw Exception::invalidDatetime();
        }

        return true;
    }

    /**
     * @return string
     */
    protected static function getRequestUri(): string
    {
        return self::REQUEST_URI;
    }

    /**
     * Define card alias in case of an express payment
     *
     * @param string $alias Alias card name
     */
    public function setCardAlias(string $alias): void
    {
        $this->options['aliascb'] = $alias;
    }

    /**
     * Force submission of card informations in case of an express payment
     *
     * @param bool $value Enable or disable submission
     */
    public function setForceCard(bool $value = true): void
    {
        $this->options['forcesaisiecb'] = ($value) ? '1' : '0';
    }

    /**
     * Bypass 3DSecure check
     *
     * @param bool $value Enable or disable bypass
     */
    public function setDisable3DS(bool $value = true): void
    {
        $this->options['3dsdebrayable'] = ($value) ? '1' : '0';
    }

    /**
     * 3DSecure V2 Choice
     *
     * @param string $choice
     * @throws PurchaseException
     */
    public function setThreeDSecureChallenge(string $choice): void
    {
        if (!in_array($choice, self::THREE_D_SECURE_CHALLENGES, true)) {
            throw PurchaseException::invalidThreeDSecureChallenge($choice);
        }

        $this->options['threeDsecureChallenge'] = $choice;
    }

    /**
     * Change company sign label on payment interface
     *
     * @param string $label New sign label content
     */
    public function setSignLabel(string $label): void
    {
        $this->options['libelleMonetique'] = $label;
    }

    /**
     * Change company sign region label on payment interface
     *
     * @param string $label New sign label content
     */
    public function setRegionSignLabel(string $label): void
    {
        $this->options['libelleMonetiqueLocalite'] = $label;
    }

    /**
     * Change payment mode partner to use
     *
     * @param string $protocole New payment partner / protocol
     */
    public function setProtocole(string $protocole): void
    {
        if (!in_array($protocole, self::PROTOCOLES, true)) {
            throw PurchaseException::invalidProtocole($protocole);
        }

        $this->options['protocole'] = $protocole;
    }

    /**
     * @param BillingAddressResource $billingAddress
     */
    public function setBillingAddress(BillingAddressResource $billingAddress): void
    {
        $this->billingAddress = $billingAddress;
    }


    /**
     * @param ShippingAddressResource $shippingAddress
     */
    public function setShippingAddress(ShippingAddressResource $shippingAddress): void
    {
        $this->shippingAddress = $shippingAddress;
    }

    /**
     * @param ClientResource $client
     */
    public function setClient(ClientResource $client): void
    {
        $this->client = $client;
    }

    /**
     * @param CartResource $cart
     */
    public function setCart(CartResource $cart): void
    {
        $this->cart = $cart;
    }

    /**
     * Disable ways of payment on payment interface
     *
     * @param array[string] $ways List of payment ways to disable
     */
    public function setDisabledPaymentWays(array $ways = []): void
    {
        $_ways = [];

        foreach ($ways as $way) {
            if (in_array($way, self::PAYMENT_WAYS, true)) {
                $_ways[] = $way;
            }
        }

        $this->options['desactivemoyenpaiement'] = implode(',', $_ways);
    }

    /**
     * Get order context
     *
     * @return string
     */
    public function orderContextBase64(): string
    {
        $contextCommand = [];

        if ($this->billingAddress) {
            $contextCommand['billing'] = $this->billingAddress->getParameters();
        }

        if ($this->shippingAddress) {
            $contextCommand['shipping'] = $this->shippingAddress->getParameters();
        }

        if ($this->client) {
            $contextCommand['client'] = $this->client->getParameters();
        }

        if ($this->cart) {
            $contextCommand['shoppingCart'] = $this->cart->getParameters();
        }

        return base64_encode(json_encode($contextCommand, JSON_UNESCAPED_UNICODE));
    }

    /**
     * @param string $eptCode
     * @param string $companyCode
     * @param string $version
     * @return array
     */
    private function baseFields(string $eptCode, string $companyCode, string $version): array
    {
        $fields = [
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

        return $fields;
    }

    /**
     * @return array
     */
    private function urlFields(): array
    {
        return [
            'url_retour_ok' => $this->successUrl,
            'url_retour_err' => $this->errorUrl,
        ];
    }

    /**
     * @return array
     */
    private function commitmentsFields(): array
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
    private function optionsFields(): array
    {
        return [
            'ThreeDSecureChallenge' => $this->options['threeDsecureChallenge'] ?? '',
            '3dsdebrayable' => $this->options['3dsdebrayable'] ?? '',
            'aliascb' => $this->options['aliascb'] ?? '',
            'desactivemoyenpaiement' => $this->options['desactivemoyenpaiement'] ?? '',
            'forcesaisiecb' => $this->options['forcesaisiecb'] ?? '',
            'libelleMonetique' => $this->options['libelleMonetique'] ?? '',
            'libelleMonetiqueLocalite' => $this->options['libelleMonetiqueLocalite'] ?? '',
        ];
    }

    /**
     * @param string $eptCode
     * @param string $companyCode
     * @param string $version
     * @return array
     */
    public function fieldsToArray(string $eptCode, string $companyCode, string $version): array
    {
        return array_merge(
            $this->baseFields($eptCode, $companyCode, $version),
            $this->optionsFields(),
            $this->commitmentsFields(),
            $this->urlFields()
        );
    }
}
