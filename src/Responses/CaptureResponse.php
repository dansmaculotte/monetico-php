<?php

namespace DansMaCulotte\Monetico\Responses;

use DansMaCulotte\Monetico\Exceptions\AuthenticationException;
use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Exceptions\CaptureException;
use DansMaCulotte\Monetico\Resources\AuthenticationResource;
use DateTime;

class CaptureResponse extends AbstractResponse
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
    public $cardBookmarked = null;

    /** @var string */
    public $cardMask = null;

    /** @var string */
    public $rejectReason = null;

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

        if (!in_array($this->returnCode, self::RETURN_CODES)) {
            throw CaptureException::invalidResponseReturnCode($this->returnCode);
        }

        if (!in_array($this->cardVerificationStatus, self::CARD_VERIFICATION_STATUSES)) {
            throw CaptureException::invalidResponseCardVerificationStatus($this->cardVerificationStatus);
        }

        if (!in_array($this->cardBrand, array_keys(self::CARD_BRANDS))) {
            throw CaptureException::invalidResponseCardBrand($this->cardBrand);
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
            'numauto' => 'authNumber',
            'originecb' => 'cardCountry',
            'bincb' => 'cardBIN',
            'hpancb' => 'cardHash',
            'ipclient' => 'clientIp',
            'originetr' => 'transactionCountry',
        ];
    }

    /**
     * @param string $authentication
     * @throws AuthenticationException
     */
    private function setAuthentication(string $authentication): void
    {
        $authentication = base64_decode($authentication);
        $authentication = json_decode($authentication);

        $this->authentication = new AuthenticationResource(
            $authentication->protocol,
            $authentication->status,
            $authentication->version,
            (array) $authentication->details
        );
    }

    /**
     * @param array $data
     * @throws CaptureException
     */
    private function setOptions(array $data): void
    {
        if (isset($data['modepaiement'])) {
            $this->paymentMethod = $data['modepaiement'];
            if (!in_array($this->paymentMethod, self::PAYMENT_METHODS)) {
                throw CaptureException::invalidResponsePaymentMethod($this->paymentMethod);
            }
        }

        // ToDo: Split amount and currency with ISO4217
        if (isset($data['montantech'])) {
            $this->commitmentAmount = $data['montantech'];
        }

        if (isset($data['cbenregistree'])) {
            $this->cardBookmarked = (bool) $data['cbenregistree'];
        }

        if (isset($data['cbmasquee'])) {
            $this->cardMask = $data['cbmasquee'];
        }
    }

    /**
     * @param array $data
     * @throws CaptureException
     */
    private function setErrorsOptions(array $data): void
    {
        if (isset($data['filtragecause'])) {
            $this->filteredReason = (int) $data['filtragecause'];
            if (!in_array($this->filteredReason, self::FILTERED_REASONS)) {
                throw CaptureException::invalidResponseFilteredReason($this->filteredReason);
            }
        }

        if (isset($data['motifrefus'])) {
            $this->rejectReason = $data['motifrefus'];
            if (!in_array($this->rejectReason, self::REJECT_REASONS)) {
                throw CaptureException::invalidResponseRejectReason($this->rejectReason);
            }
        }

        if (isset($data['filtragevaleur'])) {
            $this->filteredValue = $data['filtragevaleur'];
        }

        if (isset($data['filtrage_etat'])) {
            $this->filteredStatus = $data['filtrage_etat'];
        }
    }

    /**
     * @param string $eptCode
     * @return array
     */
    private function fieldsToArray(string $eptCode): array
    {
        $fields = [
            'TPE' => $eptCode,
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
            'numauto' => $this->authNumber,
            'originecb' => $this->cardCountry,
            'originetr' => $this->transactionCountry,
            'reference' => $this->reference,
            'texte-libre' => $this->description,
            'vld' => $this->cardExpirationDate,
        ];

        if (isset($this->rejectReason)) {
            $fields['motifrefus'] = $this->rejectReason;
        }


        if (isset($this->commitmentAmount)) {
            $fields['montantech'] = $this->commitmentAmount;
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

        if (isset($this->cardBookmarked)) {
            $fields['cbenregistree'] = $this->cardBookmarked;
        }

        if (isset($this->cardMask)) {
            $fields['cbmasquee'] = $this->cardMask;
        }

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
        $fields = $this->fieldsToArray($eptCode);

        ksort($fields);

        $query = http_build_query($fields, null, '*');
        $query = urldecode($query);

        $hash = strtoupper(hash_hmac(
            'sha1',
            $query,
            $securityKey
        ));

        return $hash == $this->seal;
    }
}