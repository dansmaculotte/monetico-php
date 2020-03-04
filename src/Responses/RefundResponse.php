<?php

namespace DansMaCulotte\Monetico\Responses;

use DansMaCulotte\Monetico\Exceptions\Exception;

class RefundResponse extends AbstractResponse
{
    /** @var string */
    public $fileNumber;

    /** @var string */
    public $invoiceType;

    /** @var array */
    const INVOICE_TYPES = [
        'preauto',
        'noshow',
        'complementaire',
    ];

    /**
     * RefundResponse constructor.
     *
     * @param array $data
     * @throws Exception
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);

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
    }
}
