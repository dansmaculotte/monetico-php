<?php

namespace DansMaCulotte\Monetico\Responses;

use DansMaCulotte\Monetico\Exceptions\AuthenticationException;
use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Exceptions\PurchaseException;
use DansMaCulotte\Monetico\Resources\AuthenticationResource;
use DateTime;

class PurchaseResponse extends AbstractResponse
{
    /** @var string */
    public $eptCode;

    /** @var \DateTime */
    public $dateTime;

    /** @var string */
    public $amount;

    /** @var string */
    public $seal;

    /** @var string */
    public $cardVerificationStatus;

    /** @var string */
    public $cardExpirationDate;

    /** @var string */
    public $cardBrand;

    /** @var string */
    public $cardCountry;

    /** @var string */
    public $cardBIN;

    /** @var string */
    public $cardHash;

    /** @var bool */
    public $cardSaved = null;

    /** @var string */
    public $cardMask = null;

    /** @var string */
    public $rejectReason = null;

    /** @var string */
    public $authorisationRejectReason = null;


    /** @var string */
    public $authNumber;

    /** @var string */
    public $clientIp;

    /** @var string */
    public $transactionCountry;

    /** @var string */
    public $paymentMethod = null;

    /** @var string */
    public $commitmentAmount = null;

    /** @var int */
    public $filteredReason = null;

    /** @var string */
    public $filteredValue = null;

    /** @var string */
    public $filteredStatus = null;

    /** @var AuthenticationResource */
    public $authentication = null;

    /** @var string */
    public $authenticationHash = null;

    /** @var string */
    public $cardType;

    /** @var string */
    public $accountType;

    /** @var string */
    public $virtualCard;

    /** @var string */
    const DATETIME_FORMAT = 'd/m/Y_\a_H:i:s';

    /** @var array */
    const RETURN_CODES = [
        'payetest',
        'paiement',
        'Annulation',
        'paiement_pf2',
        'paiement_pf3',
        'paiement_pf4',
        'Annulation_pf2',
        'Annulation_pf3',
        'Annulation_pf4',
    ];

    /** @var array */
    const CARD_VERIFICATION_STATUSES = [
        'oui',
        'non',
    ];

    /** @var array */
    const CARD_BRANDS = [
        'AM' => 'American Express',
        'CB' => 'GIE CB',
        'MC' => 'Mastercard',
        'VI' => 'Visa',
        'na' => 'Non disponible',
    ];

    /** @var array  */
    const REJECT_REASONS = [
        '',
        '-',
        'Appel Phonie',
        'Refus',
        'Interdit',
        'filtrage',
        'scoring',
        '3DSecure',
    ];

    /** @var array  */
    const PAYMENT_METHODS = [
        'CB',
        'paypal',
        '1euro',
        '3xcb',
        '4cb',
        'audiotel',
    ];

    /** @var array */
    const FILTERED_REASONS = [
        1, 2, 3, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 15, 16,
    ];

    /**
     * OutputPayload constructor.
     *
     * @param array $data
     * @throws \Exception
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->dateTime = DateTime::createFromFormat(self::DATETIME_FORMAT, $this->dateTime);
        if (!$this->dateTime instanceof DateTime) {
            throw Exception::invalidResponseDateTime();
        }

        if (!in_array($this->returnCode, self::RETURN_CODES, true)) {
            throw PurchaseException::invalidResponseReturnCode($this->returnCode);
        }

        if (!in_array($this->cardVerificationStatus, self::CARD_VERIFICATION_STATUSES, true)) {
            throw PurchaseException::invalidResponseCardVerificationStatus($this->cardVerificationStatus);
        }

        if (!array_key_exists($this->cardBrand, self::CARD_BRANDS)) {
            throw PurchaseException::invalidResponseCardBrand($this->cardBrand);
        }

        $this->setAuthentication($this->authenticationHash);
        $this->setOptions($data);
        $this->setErrorsOptions($data);
    }

    public function getRequiredKeys(): array
    {
        return [
            'TPE' => 'eptCode',
            'date' => 'dateTime',
            'montant' => 'amount',
            'reference' => 'reference',
            'MAC' => 'seal',
            'authentification' => 'authenticationHash',
            'texte-libre' => 'description',
            'code-retour' => 'returnCode',
            'cvx' => 'cardVerificationStatus',
            'vld' => 'cardExpirationDate',
            'brand' => 'cardBrand',
            'originecb' => 'cardCountry',
            'bincb' => 'cardBIN',
            'hpancb' => 'cardHash',
            'ipclient' => 'clientIp',
            'originetr' => 'transactionCountry',
            'usage' => 'cardType',
            'typecompte' => 'accountType',
            'ecard' => 'virtualCard',
        ];
    }

    /**
     * @param string $authentication
     * @throws AuthenticationException
     */
    private function setAuthentication(string $authentication): void
    {
        $authentication = base64_decode($authentication);
        $authentication = json_decode($authentication, true);

        if ($authentication) {
            $this->authentication = new AuthenticationResource(
                $authentication['protocol'],
                $authentication['status'],
                $authentication['version'],
                $authentication->details ?? []
            );
        }
    }

    /**
     * @param array $data
     * @throws PurchaseException
     */
    private function setOptions(array $data): void
    {
        if (isset($data['numauto'])) {
            $this->authNumber = $data['numauto'];
        }

        if (isset($data['modepaiement'])) {
            $this->paymentMethod = $data['modepaiement'];
            if (!in_array($this->paymentMethod, self::PAYMENT_METHODS, true)) {
                throw PurchaseException::invalidResponsePaymentMethod($this->paymentMethod);
            }
        }

        // ToDo: Split amount and currency with ISO4217
        if (isset($data['montantech'])) {
            $this->commitmentAmount = $data['montantech'];
        }

        if (isset($data['cbenregistree'])) {
            $this->cardSaved = (bool) $data['cbenregistree'];
        }

        if (isset($data['cbmasquee'])) {
            $this->cardMask = $data['cbmasquee'];
        }
    }

    /**
     * @param array $data
     * @throws PurchaseException
     */
    private function setErrorsOptions(array $data): void
    {
        if (isset($data['filtragecause'])) {
            $this->filteredReason = (int) $data['filtragecause'];
            if (!in_array($this->filteredReason, self::FILTERED_REASONS, true)) {
                throw PurchaseException::invalidResponseFilteredReason($this->filteredReason);
            }
        }

        if (isset($data['motifrefus'])) {
            $this->rejectReason = $data['motifrefus'];
            if (!in_array($this->rejectReason, self::REJECT_REASONS, true)) {
                throw PurchaseException::invalidResponseRejectReason($this->rejectReason);
            }
        }

        if (isset($data['motifrefusautorisation'])) {
            $this->authorisationRejectReason = $data['motifrefusautorisation'];
        }

        if (isset($data['filtragevaleur'])) {
            $this->filteredValue = $data['filtragevaleur'];
        }

        if (isset($data['filtrage_etat'])) {
            $this->filteredStatus = $data['filtrage_etat'];
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $fields = [
            'authentification' => $this->authenticationHash,
            'bincb' => $this->cardBIN,
            'brand' => $this->cardBrand,
            'code-retour' => $this->returnCode,
            'cvx' => $this->cardVerificationStatus,
            'date' => $this->dateTime->format(self::DATETIME_FORMAT),
            'hpancb' => $this->cardHash,
            'ipclient' => $this->clientIp,
            'modepaiement' => $this->paymentMethod,
            'montant' => $this->amount,
            'originecb' => $this->cardCountry,
            'originetr' => $this->transactionCountry,
            'reference' => $this->reference,
            'texte-libre' => $this->description,
            'vld' => $this->cardExpirationDate,
            'usage' => $this->cardType,
            'typecompte' => $this->accountType,
            'ecard' => $this->virtualCard,
        ];

        if (isset($this->authNumber)) {
            $fields['numauto'] = $this->authNumber;
        }

        if (isset($this->rejectReason)) {
            $fields['motifrefus'] = $this->rejectReason;
        }

        if (isset($this->authorisationRejectReason)) {
            $fields['motifrefusautorisation'] = $this->authorisationRejectReason;
        }

        if (isset($this->commitmentAmount)) {
            $fields['montantech'] = $this->commitmentAmount;
        }

        if (isset($this->folderNumber)) {
            $fields['numerodossier'] = $this->folderNumber;
        }

        if (isset($this->invoiceType)) {
            $fields['typefacture'] = $this->invoiceType;
        }

        if (isset($this->filteredReason)) {
            $fields['filtragecause'] = $this->filteredReason;
        }

        if (isset($this->filteredValue)) {
            $fields['filtragevaleur'] = $this->filteredValue;
        }

        if (isset($this->filteredStatus)) {
            $fields['filtrage_etat'] = $this->filteredStatus;
        }

        if (isset($this->cardSaved)) {
            $fields['cbenregistree'] = $this->cardSaved;
        }

        if (isset($this->cardMask)) {
            $fields['cbmasquee'] = $this->cardMask;
        }

        if (isset($this->paymentMethod)) {
            $fields['modepaiement'] = $this->paymentMethod;
        }

        // if(isset($this->authentication)) {
        //}

        return $fields;
    }

    /**
     * Validate seal to verify payment
     *
     * @param string $eptCode
     * @param string $securityKey
     * @return bool
     */
    public function validateSeal(string $eptCode, string $securityKey): bool
    {
        $fields = array_merge(
            [
                'TPE' => $eptCode,
            ],
            $this->toArray()
        );

        ksort($fields);

        $query = http_build_query($fields, null, '*');
        $query = urldecode($query);

        $hash = strtoupper(hash_hmac(
            'sha1',
            $query,
            $securityKey
        ));

        return $hash === $this->seal;
    }
}
