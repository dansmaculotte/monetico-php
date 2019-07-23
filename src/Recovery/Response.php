<?php

namespace DansMaCulotte\Monetico\Recovery;

use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Exceptions\RecoveryException;
use DateTime;

class Response
{
    /** @var float */
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
    public $authorisationDate;

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

    /** @var array  */
    const INVOICE_TYPES = [
        'preauto',
        'noshow',
    ];

    /** @var string */
    const DATE_FORMAT = 'Y-m-d';

    /**
     * RecoveryResponse constructor.
     *
     * @param array $data
     *
     * @throws Exception
     * @throws RecoveryException
     */
    public function __construct($data = [])
    {
        $this->version = self::SERVICE_VERSION;

        $requiredKeys = [
            'cdr',
            'lib',
            'reference',
        ];

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
            $this->authorisationDate = DateTime::createFromFormat(self::DATE_FORMAT, $data['date_autorisation']);
            if (!$this->authorisationDate instanceof DateTime) {
                throw RecoveryException::invalidResponseAuthorizationDate();
            }
        }

        if (isset($data['montant_debite'])) {
            $this->amountDebited = $data['montant_debite'];
        }

        if (isset($data['date_debit'])) {
            $this->debitDatetime = DateTime::createFromFormat(self::DATE_FORMAT, $data['date_debit']);
            if (!$this->authorisationDate instanceof DateTime) {
                throw RecoveryException::invalidResponseDebitDate();
            }
        }

        if (isset($data['numero_dossier'])) {
            $this->fileNumber = $data['numero_dossier'];
            if (strlen($this->fileNumber) > 12) {
                throw Exception::invalidResponseFileNumber($this->fileNumber);
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
