<?php

namespace DansMaCulotte\Monetico\Refund;


use DansMaCulotte\Monetico\Exceptions\Exception;

class Refund
{
    /** @var \DateTime */
    public $datetime;

    /** @var \DateTime */
    public $orderDatetime;

    /** @var \DateTime */
    public $recoveryDatetime;

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

    const INVOICE_TYPES = [
        'preauto',
        'noshow',
    ];

    /**
     * Refund constructor.
     * @param array $data
     * @throws Exception
     */
    public function __construct($data = array())
    {
        $this->datetime = $data['datetime'];
        if (!is_a($this->datetime, 'DateTime')) {
            throw Exception::invalidDatetime();
        }

        $this->orderDatetime = $data['orderDatetime'];
        if (!is_a($this->orderDatetime, 'DateTime')) {
            throw Exception::invalidOrderDatetime();
        }

        $this->recoveryDatetime = $data['recoveryDatetime'];
        if (!is_a($this->recoveryDatetime, 'DateTime')) {
            throw Exception::invalidRecoveryDatetime();
        }

        $this->authorizationNumber = $data['authorizationNumber'];

        $this->currency = $data['currency'];

        $this->amount = $data['amount'];
        $this->refundAmount = $data['refundAmount'];
        $this->maxRefundAmount = $data['maxRefundAmount'];

        $this->reference = $data['reference'];
        if (strlen($this->reference) > 12) {
            throw Exception::invalidReference($this->reference);
        }

        $this->language = $data['language'];
        if (strlen($this->language) != 2) {
            throw Exception::invalidLanguage($this->language);
        }
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
            'date' => $this->datetime->format('d/m/Y:H:i:s'),
            'date_commande' => $this->orderDatetime->format('d/m/Y'),
            'date_remise' => $this->recoveryDatetime->format('d/m/Y'),
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
            array_merge($fields, ['numero_dossier' => $this->fileNumber]);
        }

        if (isset($this->invoiceType)) {
            array_merge($fields, ['facture' => $this->invoiceType]);
        }

        return $fields;
    }

    /**
     * @param $eptCode
     * @param $securityKey
     * @param $version
     * @param $companyCode
     * @return string
     */
    public function generateSeal($eptCode, $securityKey, $version, $companyCode)
    {
        $fields = $this->fieldsToArray($eptCode, $version, $companyCode);

        ksort($fields);
        $query = urldecode(http_build_query($fields, null, '*'));

        return strtoupper(hash_hmac(
            'sha1',
            $query,
            $securityKey
        ));
    }

    public function generateFields($eptCode, $seal, $version, $companyCode) {
        return array_merge(
            $this->fieldsToArray($eptCode, $version, $companyCode),
            ['MAC' => $seal]
        );
    }

}