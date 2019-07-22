<?php

namespace DansMaCulotte\Monetico\Refund;


use DansMaCulotte\Monetico\BaseMethod;
use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\iMethod;

class Refund implements iMethod
{
    use BaseMethod;

    /** @var \DateTime */
    public $datetime;

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

    /**
     * Refund constructor.
     * @param array $data
     * @throws Exception
     */
    public function __construct($data = array())
    {
        $this->datetime = $data['datetime'];
        $this->orderDate = $data['orderDatetime'];
        $this->recoveryDate = $data['recoveryDatetime'];
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
        if (!in_array($invoiceType, self::INVOICE_TYPES)) {
            throw Exception::invalidInvoiceType($invoiceType);
        }
        $this->invoiceType = $invoiceType;
    }

    public function fieldsToArray($eptCode, $version, $companyCode)
    {
        $fields = array_merge([
            'TPE' => $eptCode,
            'date' => $this->datetime->format(self::DATETIME_FORMAT),
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
    public function validate()
    {
        if (!$this->datetime instanceof DateTime) {
            throw Exception::invalidDatetime();
        }

        if (!$this->orderDate instanceof DateTime) {
            throw Exception::invalidOrderDatetime();
        }

        if (!$this->recoveryDate instanceof DateTime) {
            throw Exception::invalidRecoveryDatetime();
        }

        if (strlen($this->reference) > 12) {
            throw Exception::invalidReference($this->reference);
        }

        if (strlen($this->language) != 2) {
            throw Exception::invalidLanguage($this->language);
        }
    }

}