<?php

namespace DansMaCulotte\Monetico\Refund;

use DansMaCulotte\Monetico\Exceptions\Exception;

class RefundResponse
{
    const SERVICE_VERSION = 1.0;

    /** @var string */
    public $returnCode;


    /** @var string */
    public $description;

    /** @var float  */
    public $version;

    /** @var string  */
    public $reference;

    /** @var string */
    public $fileNumber;

    /** @var string */
    public $invoiceType;

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
    public function __construct($data = array())
    {
        $this->version = self::SERVICE_VERSION;

        $this->returnCode = $data['cdr'];

        $this->description = $data['lib'];

        $this->reference = $data['reference'];
        if (strlen($this->reference) > 12) {
            throw Exception::invalidReference($this->reference);
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
                throw Exception::invalidInvoiceType($this->invoiceType);
            }
        }
    }

}