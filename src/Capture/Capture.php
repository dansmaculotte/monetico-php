<?php

namespace DansMaCulotte\Monetico\Capture;


use DansMaCulotte\Monetico\Exceptions\CaptureException;

class Capture
{

    /** @var \DateTime */
    public $datetime;

    /** @var \DateTime */
    public $orderDatetime;

    /** @var float */
    public $amount;

    /** @var string */
    public $currency;

    /** @var float */
    public $amountToCapture;

    /** @var float */
    public $amountCaptured;

    /** @var float */
    public $amountLeft;

    /** @var string */
    public $reference;

    /** @var string */
    public $language;

    /** @var string */
    public $stopRecurrence;

    /** @var string */
    public $fileNumber;

    /** @var string */
    public $invoiceType;

    const INVOICE_TYPES = [
        'preauto',
        'noshow',
    ];

    /** @var string */
    public $phone;


    /**
     * Capture constructor.
     * @param array $data
     * @throws CaptureException
     */
    public function __construct($data = array())
    {

        $this->datetime = $data['datetime'];
        if (!is_a($this->datetime, 'DateTime')) {
            throw CaptureException::invalidDatetime();
        }

        $this->orderDatetime = $data['orderDatetime'];
        if (!is_a($this->orderDatetime, 'DateTime')) {
            throw CaptureException::invalidOrderDatetime();
        }

        $this->reference = $data['reference'];
        if (strlen($this->reference) > 12) {
            throw CaptureException::invalidReference($this->reference);
        }

        $this->language = $data['language'];
        if (strlen($this->language) != 2) {
            throw CaptureException::invalidLanguage($this->language);
        }

        $this->currency = $data['currency'];

        $this->amount = $data['amount'];
        $this->amountToCapture = $data['amountToCapture'];
        $this->amountCaptured = $data['amountCaptured'];
        $this->amountLeft = $data['amountLeft'];

        if ($this->amountLeft + $this->amountCaptured + $this->amountToCapture !== $this->amount) {
            throw CaptureException::invalidAmounts($this->amount, $this->amountToCapture, $this->amountCaptured, $this->amountLeft);
        }
    }

    /**
     * @param bool $value
     */
    public function setStopRecurrence($value = true)
    {
        $this->stopRecurrence = ($value) ? 'oui' : '0';
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
     *
     * @throws CaptureException
     */
    public function setInvoiceType(string $invoiceType)
    {
        if (!in_array($invoiceType, self::INVOICE_TYPES)) {
            throw CaptureException::invalidInvoiceType($invoiceType);
        }
        $this->invoiceType = $invoiceType;
    }

    /**
     * @param bool $value
     */
    public function setPhone(bool $value = true)
    {
        $this->phone = ($value) ? 'oui' : '0';
    }


    public function fieldsToArray($eptCode, $version, $companyCode)
    {
        $fields = array_merge([
            'TPE' => $eptCode,
            'date' => $this->datetime->format('d/m/Y:H:i:s'),
            'date_commande' => $this->orderDatetime->format('d/m/Y'),
            'lgue' => $this->language,
            'montant' => $this->amount . $this->currency,
            'montant_a_capturer' => $this->amountToCapture . $this->currency,
            'montant_deja_capture' => $this->amountCaptured . $this->currency,
            'montant_restant' => $this->amountLeft . $this->currency,
            'reference' => $this->reference,
            'societe' => $companyCode,
            'version' => $version
        ]);

        if (isset($this->stopRecurrence)) {
            array_merge($fields, ['stoprecurrence' => $this->stopRecurrence]);
        }

        if (isset($this->fileNumber)) {
            array_merge($fields, ['numero_dossier' => $this->fileNumber]);
        }

        if (isset($this->invoiceType)) {
            array_merge($fields, ['facture' => $this->invoiceType]);
        }

        if (isset($this->phone)) {
            array_merge($fields, ['phonie' => $this->phone]);
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

        print_r($query);
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