<?php

namespace DansMaCulotte\Monetico\Recovery;


use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Exceptions\RecoveryException;
use DateTime;

class RecoveryResponse
{
    const SERVICE_VERSION = 1.0;

    /** @var int */
    public $returnCode;

    /** @var string */
    public $description;

    /** @var float */
    public $version;

    /** @var string */
    public $reference;

    /** @var string */
    public $authorisationNumber;

    /** @var string */
    public $phone;

    /** @var float */
    public $estimatedAmount;

    /** @var \DateTime */
    public $authorisationDatetime;

    /** @var string */
    public $currency;

    /** @var float */
    public $amountDebited;

    /** @var \DateTime */
    public $debitDatetime;

    /** @var string */
    public $fileNumber;

    /** @var string */
    public $invoiceType;

    const INVOICE_TYPES = [
        'preauto',
        'noshow',
    ];

    /**
     * RecoveryResponse constructor.
     *
     * @param array $data
     *
     * @throws Exception
     * @throws RecoveryException
     */
    public function __construct($data = array())
    {
        $this->version = self::SERVICE_VERSION;

        $requiredKeys = array(
            'cdr',
            'lib',
            'reference',
        );

        foreach ($requiredKeys as $key) {
            if (!in_array($key, array_keys($data))) {
                throw Exception::missingResponseKey($key);
            }
        }

        $this->returnCode = $data['cdr'];
        $this->description = $data['lib'];
        $this->reference = $data['reference'];

        if (isset($data['aut'])) {
            $this->authorisationNumber = $data['aut'];
        }

        if (isset($data['montant_estime'])) {
            $this->estimatedAmount = $data['montant_estime'];
        }

        if (isset($data['date_autorisation'])) {
            $this->authorisationDatetime = date_create($data['date_autorisation']);
            if (!$this->authorisationDatetime instanceof DateTime) {
                throw Exception::invalidResponseDateTime();
            }
        }

        if (isset($data['montant_debite'])) {
            $this->amountDebited = $data['montant_debite'];
        }

        if (isset($data['date_debit'])) {
            $this->debitDatetime = date_create($data['date_debit']);
            if (!$this->authorisationDatetime instanceof DateTime) {
                throw RecoveryException::invalidResponseDebitDatetime();
            }
        }

        if (isset($data['numero_dossier'])) {
            $this->fileNumber = $data['numero_dossier'];
            if (strlen($this->fileNumber) > 12) {
                throw Exception::invalidReference($this->fileNumber);
            }
        }

        if (isset($data['type_facture'])) {
            $this->invoiceType = $data['type_facture'];
            if (!in_array($this->invoiceType, self::INVOICE_TYPES)) {
                throw Exception::invalidResponseInvoiceType($this->invoiceType);
            }
        }

        if (isset($data['phonie'])) {
            $this->phone = $data['phonie'];
        }

    }


}