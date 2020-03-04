<?php

namespace DansMaCulotte\Monetico\Requests;

use DansMaCulotte\Monetico\Exceptions\Exception;
use DateTime;

class RefundRequest extends AbstractRequest
{
    /** @var \DateTime */
    public $dateTime;

    /** @var \DateTime */
    public $orderDate;

    /** @var \DateTime */
    public $recoveryDate;

    /** @var string */
    public $authorizationNumber;

    /** @var string */
    public $currency;

    /** @var float */
    public $amount;

    /** @var float */
    public $refundAmount;

    /** @var float */
    public $maxRefundAmount;

    /** @var string */
    public $reference;

    /** @var string */
    public $language;

    /** @var string */
    public $fileNumber;

    /** @var string */
    public $invoiceType;

    /** @var array */
    const INVOICE_TYPES = [
        'preauto',
        'noshow',
    ];

    /** @var string */
    const DATETIME_FORMAT = 'd/m/Y:H:i:s';

    /** @var string */
    const DATE_FORMAT = 'd/m/Y';

    /** @var string */
    const REQUEST_URI = 'recredit_paiement.cgi';

    /**
     * Refund constructor.
     * @param array $data
     * @throws Exception
     */
    public function __construct($data = [])
    {
        $this->dateTime = $data['dateTime'];
        $this->orderDate = $data['orderDate'];
        $this->recoveryDate = $data['recoveryDate'];
        $this->authorizationNumber = $data['authorizationNumber'];
        $this->currency = $data['currency'];
        $this->amount = $data['amount'];
        $this->refundAmount = $data['refundAmount'];
        $this->maxRefundAmount = $data['maxRefundAmount'];
        $this->reference = $data['reference'];
        $this->language = $data['language'];

        $this->validate();
    }

    /**
     * @return string
     */
    protected static function getRequestUri(): string
    {
        return self::REQUEST_URI;
    }

    /**
     * @param $value
     */
    public function setFileNumber($value)
    {
        $this->fileNumber = $value;
    }


    /**
     * @param string $invoiceType
     * @throws Exception
     */
    public function setInvoiceType(string $invoiceType)
    {
        if (!in_array($invoiceType, self::INVOICE_TYPES, true)) {
            throw Exception::invalidInvoiceType($invoiceType);
        }
        $this->invoiceType = $invoiceType;
    }

    /**
     * @param string $eptCode
     * @param string $version
     * @param string $companyCode
     * @return array
     */
    public function fieldsToArray(string $eptCode, string $version, string $companyCode): array
    {
        $fields = array_merge([
            'TPE' => $eptCode,
            'date' => $this->dateTime->format(self::DATETIME_FORMAT),
            'date_commande' => $this->orderDate->format(self::DATE_FORMAT),
            'date_remise' => $this->recoveryDate->format(self::DATE_FORMAT),
            'num_autorisation' => $this->authorizationNumber,
            'montant' => $this->amount . $this->currency,
            'montant_recredit' => $this->refundAmount . $this->currency,
            'montant_possible' => $this->maxRefundAmount . $this->currency,
            'reference' => $this->reference,
            'lgue' => $this->language,
            'societe' => $companyCode,
            'version' => $version
        ]);

        if (isset($this->fileNumber)) {
            $fields['numero_dossier'] = $this->fileNumber;
        }

        if (isset($this->invoiceType)) {
            $fields['facture'] = $this->invoiceType;
        }

        return $fields;
    }

    /**
     * @throws Exception
     */
    public function validate(): bool
    {
        if (!$this->dateTime instanceof DateTime) {
            throw Exception::invalidDatetime();
        }

        if (!$this->orderDate instanceof DateTime) {
            throw Exception::invalidOrderDate();
        }

        if (!$this->recoveryDate instanceof DateTime) {
            throw Exception::invalidRecoveryDate();
        }

        if (strlen($this->reference) > 12) {
            throw Exception::invalidReference($this->reference);
        }

        if (strlen($this->language) !== 2) {
            throw Exception::invalidLanguage($this->language);
        }

        return true;
    }
}
