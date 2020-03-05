<?php

namespace DansMaCulotte\Monetico\Responses;

use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Exceptions\RecoveryException;
use DateTime;

class RecoveryResponse extends AbstractResponse
{
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
    public $debitDate;

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
     * @throws Exception
     * @throws RecoveryException
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->setOptions($data);
        $this->setDates($data);
        $this->setAmounts($data);
    }

    /**
     * @param array $data
     * @throws RecoveryException
     */
    private function setDates($data): void
    {
        if (isset($data['date_autorisation'])) {
            $this->authorisationDate = DateTime::createFromFormat(self::DATE_FORMAT, $data['date_autorisation']);
            if (!$this->authorisationDate) {
                throw RecoveryException::invalidResponseAuthorizationDate();
            }
        }

        if (isset($data['date_debit'])) {
            $this->debitDate = DateTime::createFromFormat(self::DATE_FORMAT, $data['date_debit']);
            if (!$this->authorisationDate) {
                throw RecoveryException::invalidResponseDebitDate();
            }
        }
    }

    /**
     * @param array $data
     * @throws Exception
     */
    public function setOptions(array $data): void
    {
        if (isset($data['aut'])) {
            $this->authorisationNumber = $data['aut'];
        }

        if (isset($data['numero_dossier'])) {
            $this->fileNumber = $data['numero_dossier'];
            if (strlen($this->fileNumber) > 12) {
                throw Exception::invalidResponseFileNumber($this->fileNumber);
            }
        }

        if (isset($data['type_facture'])) {
            $this->invoiceType = $data['type_facture'];
            if (!in_array($this->invoiceType, self::INVOICE_TYPES, true)) {
                throw Exception::invalidResponseInvoiceType($this->invoiceType);
            }
        }

        if (isset($data['phonie'])) {
            $this->phone = $data['phonie'];
        }
    }

    /**
     * @param array $data
     */
    private function setAmounts(array $data): void
    {
        if (isset($data['montant_estime'])) {
            $this->estimatedAmount = $data['montant_estime'];
        }
        if (isset($data['montant_debite'])) {
            $this->amountDebited = $data['montant_debite'];
        }
    }
}
