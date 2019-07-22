<?php

namespace DansMaCulotte\Monetico\Recovery;

use DansMaCulotte\Monetico\BaseMethod;
use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Exceptions\RecoveryException;
use DansMaCulotte\Monetico\iMethod;

class Recovery implements iMethod
{
    use BaseMethod;


    /** @var \DateTime */
    public $dateTime;

    /** @var \DateTime */
    public $orderDate;

    /** @var float */
    public $amount;

    /** @var string */
    public $currency;

    /** @var float */
    public $amountToRecover;

    /** @var float */
    public $amountRecovered;

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

    /** @var array  */
    const INVOICE_TYPES = [
        'preauto',
        'noshow',
    ];

    /** @var string */
    public $phone;

    /** @var string */
    const DATETIME_FORMAT = 'd/m/Y:H:i:s';

    /** @var string */
    const DATE_FORMAT = 'd/m/Y';


    /**
     * Recovery constructor.
     * @param array $data
     * @throws RecoveryException
     * @throws Exception
     */
    public function __construct($data = array())
    {

        $this->dateTime = $data['dateTime'];

        $this->orderDate = $data['orderDate'];

        $this->reference = $data['reference'];

        $this->language = $data['language'];

        $this->currency = $data['currency'];

        $this->amount = $data['amount'];
        $this->amountToRecover = $data['amountToRecover'];
        $this->amountRecovered = $data['amountRecovered'];
        $this->amountLeft = $data['amountLeft'];

        $this->validate();
    }

    /**
     * @throws Exception
     * @throws RecoveryException
     */
    public function validate()
    {
        if (!$this->dateTime instanceof DateTime) {
            throw Exception::invalidDatetime();
        }

        if (!$this->orderDate instanceof DateTime) {
            throw Exception::invalidOrderDatetime();
        }

        if (strlen($this->reference) > 12) {
            throw Exception::invalidReference($this->reference);
        }

        if (strlen($this->language) != 2) {
            throw Exception::invalidLanguage($this->language);
        }

        if ($this->amountLeft + $this->amountRecovered + $this->amountToRecover !== $this->amount) {
            throw RecoveryException::invalidAmounts($this->amount, $this->amountToRecover, $this->amountRecovered, $this->amountLeft);
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
     * @throws Exception
     */
    public function setInvoiceType(string $invoiceType)
    {
        if (!in_array($invoiceType, self::INVOICE_TYPES)) {
            throw Exception::invalidInvoiceType($invoiceType);
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
            'date' => $this->dateTime->format(self::DATETIME_FORMAT),
            'date_commande' => $this->orderDate->format(self::DATE_FORMAT),
            'lgue' => $this->language,
            'montant' => $this->amount . $this->currency,
            'montant_a_capturer' => $this->amountToRecover . $this->currency,
            'montant_deja_capture' => $this->amountRecovered . $this->currency,
            'montant_restant' => $this->amountLeft . $this->currency,
            'reference' => $this->reference,
            'societe' => $companyCode,
            'version' => $version
        ]);

        if (isset($this->stopRecurrence)) {
            $fields['stoprecurrence'] = $this->stopRecurrence;
        }

        if (isset($this->fileNumber)) {
            $fields['numero_dossier'] = $this->fileNumber;
        }

        if (isset($this->invoiceType)) {
            $fields['facture'] = $this->invoiceType;
        }

        if (isset($this->phone)) {
            $fields['phonie'] = $this->phone;
        }

        return $fields;
    }
}