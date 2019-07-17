<?php

namespace DansMaCulotte\Monetico\Payment;

use DansMaCulotte\Monetico\Exceptions\PaymentException;
use DateTime;

class PaymentResponse
{
    /** @var string */
    private $eptCode;

    /** @var \DateTime */
    public $datetime;

    /** @var string */
    public $amount;

    /** @var string */
    public $reference;

    /** @var string */
    public $seal;

    /** @var string */
    public $description;

    /** @var string */
    public $returnCode;

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

    /** @var int */
    public $DDDSStatus;

    /** @var string */
    public $rejectReason = null;

    /** @var string */
    public $authNumber;

    /** @var string */
    public $clientIp;

    /** @var string */
    public $transactionCountry;

    /** @var string */
    public $veresStatus;

    /** @var string */
    public $paresStatus;

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

    public $authentication = [];

    public $authenticationHash = null;

    /** @var string */
    const DATETIME_FORMAT = 'd/m/Y_\a_H:i:s';

    /** @var array */
    const RETURN_CODES = array(
        'payetest',
        'paiement',
        'Annulation',
        'paiement_pf2',
        'paiement_pf3',
        'paiement_pf4',
        'Annulation_pf2',
        'Annulation_pf3',
        'Annulation_pf4',
    );

    /** @var array */
    const CARD_VERIFICATION_STATUSES = array(
        'oui',
        'non',
    );

    /** @var array */
    const CARD_BRANDS = array(
        'AM' => 'American Express',
        'CB' => 'GIE CB',
        'MC' => 'Mastercard',
        'VI' => 'Visa',
        'na' => 'Non disponible',
    );

    /** @var array  */
    const DDDS_STATUSES = array(
        -1, 1, 4,
    );

    /** @var array  */
    const REJECT_REASONS = array(
        'Appel Phonie',
        'Refus',
        'Interdit',
        'filtrage',
        'scoring',
        '3DSecure',
    );

    /** @var array  */
    const PAYMENT_METHODS = array(
        'CB',
        'paypal',
        '1euro',
        '3xcb',
        '4cb',
        'audiotel',
    );

    /** @var array */
    const FILTERED_REASONS = array(
        1, 2, 3, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 15, 16,
    );

    /**
     * OutputPayload constructor.
     *
     * @param array $data
     *
     * @throws \Exception
     */
    public function __construct($data = array())
    {
        $requiredKeys = array(
            'TPE',
            'date',
            'montant',
            'reference',
            'MAC',
            'authentification',
            'texte-libre',
            'code-retour',
            'cvx',
            'vld',
            'brand',
            'numauto',
            'originecb',
            'bincb',
            'hpancb',
            'ipclient',
            'originetr',
        );

        foreach ($requiredKeys as $key) {
            if (!in_array($key, array_keys($data))) {
                throw PaymentException::missingResponseKey($key);
            }
        }

        $this->datetime = DateTime::createFromFormat(self::DATETIME_FORMAT, $data['date']);
        if (!is_a($this->datetime, 'DateTime')) {
            throw PaymentException::invalidDatetime();
        }

        $this->eptCode = $data['TPE'];


        // ToDo: Split amount and currency with ISO4217
        $this->amount = $data['montant'];

        $this->reference = $data['reference'];

        $this->seal = $data['MAC'];

        $this->description = $data['texte-libre'];

        $this->authenticationHash = $data['authentification'];
        $this->authentication = json_decode(base64_decode($data['authentification']), true);

        $this->returnCode = $data['code-retour'];
        if (!in_array($this->returnCode, self::RETURN_CODES)) {
            throw PaymentException::invalidReturnCode($this->returnCode);
        }

        $this->cardVerificationStatus = $data['cvx'];
        if (!in_array($this->cardVerificationStatus, self::CARD_VERIFICATION_STATUSES)) {
            throw PaymentException::invalidCardVerificationStatus($this->cardVerificationStatus);
        }

        $this->cardExpirationDate = $data['vld'];

        $this->cardBrand = $data['brand'];
        if (!in_array($this->cardBrand, array_keys(self::CARD_BRANDS))) {
            throw PaymentException::invalidCardBrand($this->cardBrand);
        }


        if (isset($data['motifrefus'])) {
            $this->rejectReason = $data['motifrefus'];
            if (!in_array($this->rejectReason, self::REJECT_REASONS)) {
                throw PaymentException::invalidRejectReason($this->rejectReason);
            }
        }

        $this->authNumber = $data['numauto'];

        // ToDo: Check Country
        $this->cardCountry = $data['originecb'];

        $this->cardBIN = $data['bincb'];
        $this->cardHash = $data['hpancb'];

        $this->clientIp = $data['ipclient'];

        // ToDo: Check Country
        $this->transactionCountry = $data['originetr'];

        if (isset($data['modepaiement'])) {
            $this->paymentMethod = $data['modepaiement'];
            if (!in_array($this->paymentMethod, self::PAYMENT_METHODS)) {
                throw PaymentException::invalidPaymentMethod($this->paymentMethod);
            }
        }

        // ToDo: Split amount and currency with ISO4217
        if (isset($data['montantech'])) {
            $this->commitmentAmount = $data['montantech'];
        }

        if (isset($data['filtragecause'])) {
            $this->filteredReason = (int) $data['filtragecause'];
            if (!in_array($this->filteredReason, self::FILTERED_REASONS)) {
                throw PaymentException::invalidFilteredReason($this->filteredReason);
            }
        }

        if (isset($data['filtragevaleur'])) {
            $this->filteredValue = $data['filtragevaleur'];
        }

        if (isset($data['filtrage_etat'])) {
            $this->filteredStatus = $data['filtrage_etat'];
        }

        if (isset($data['cbenregistree'])) {
            $this->cardBookmarked = (bool) $data['cbenregistree'];
        }

        if (isset($data['cbmasquee'])) {
            $this->cardMask = $data['cbmasquee'];
        }
    }

    /**
     * Validate seal to verify payment
     *
     * @param string $eptCode
     * @param string $securityKey
     * @param string $version
     *
     * @return bool
     */
    public function validateSeal($eptCode, $securityKey, $version)
    {
        $fields = [
            'TPE' => $eptCode,
            'authentification' => $this->authenticationHash,
            'bincb' => $this->cardBIN,
            'brand' => $this->cardBrand,
            'code-retour' => $this->returnCode,
            'cvx' => $this->cardVerificationStatus,
            'date' => $this->datetime->format(self::DATETIME_FORMAT),
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

        ksort($fields);
        $query = urldecode(http_build_query($fields, null, '*'));

        $hash =  strtoupper(hash_hmac(
            'sha1',
            $query,
            $securityKey
        ));

        return $hash == $this->seal;
    }
}