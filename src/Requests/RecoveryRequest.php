<?php

namespace DansMaCulotte\Monetico\Requests;

use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Exceptions\RecoveryException;
use DateTime;

class RecoveryRequest extends AbstractRequest
{
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

    /** @var string */
    const REQUEST_URI = 'capture_paiement.cgi';

    /**
     * Recovery constructor.
     * @param array $data
     * @throws RecoveryException
     * @throws Exception
     */
    public function __construct(array $data = [])
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
    public function validate(): bool
    {
        if (!$this->dateTime instanceof DateTime) {
            throw Exception::invalidDatetime();
        }

        if (!$this->orderDate instanceof DateTime) {
            throw Exception::invalidOrderDate();
        }

        if (strlen($this->reference) > 50) {
            throw Exception::invalidReference($this->reference);
        }

        if (strlen($this->language) !== 2) {
            throw Exception::invalidLanguage($this->language);
        }

        if ($this->amountLeft + $this->amountRecovered + $this->amountToRecover !== $this->amount) {
            throw RecoveryException::invalidAmounts($this->amount, $this->amountToRecover, $this->amountRecovered, $this->amountLeft);
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
     * @param bool $value
     */
    public function setStopRecurrence(bool $value = true): void
    {
        $this->stopRecurrence = ($value) ? 'oui' : '0';
    }

    /**
     * @param string $value
     */
    public function setFileNumber(string $value): void
    {
        $this->fileNumber = $value;
    }

    /**
     * @param string $invoiceType
     * @throws Exception
     */
    public function setInvoiceType(string $invoiceType): void
    {
        if (!in_array($invoiceType, self::INVOICE_TYPES, true)) {
            throw Exception::invalidInvoiceType($invoiceType);
        }
        $this->invoiceType = $invoiceType;
    }

    /**
     * @param bool $value
     */
    public function setPhone(bool $value = true): void
    {
        $this->phone = ($value) ? 'oui' : '0';
    }


    /**
     * @param string $eptCode
     * @param string $companyCode
     * @param string $version
     * @return array
     */
    public function fieldsToArray(string $eptCode, string $companyCode, string $version): array
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
