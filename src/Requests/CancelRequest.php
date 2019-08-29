<?php

namespace DansMaCulotte\Monetico\Requests;

use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Exceptions\RecoveryException;
use DateTime;

class CancelRequest extends AbstractRequest
{
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
        $this->validateData($data);

        $this->dateTime = $data['dateTime'];
        $this->orderDate = $data['orderDate'];
        $this->reference = $data['reference'];
        $this->language = $data['language'];
        $this->currency = $data['currency'];
        $this->amount = $data['amount'];
        $this->amountToRecover = $data['amountToRecover'];
        $this->amountRecovered = $data['amountRecovered'];
        $this->amountLeft = $data['amountLeft'];

    }

    /**
     * @return string
     */
    protected static function getRequestUri(): string
    {
        return self::REQUEST_URI;
    }

    /**
     * @param array $data
     * @return bool
     * @throws Exception
     * @throws RecoveryException
     */
    public function validateData(array $data = []): bool
    {
        if (isset($data['dateTime']) === false || $data['dateTime'] instanceof DateTime === false) {
            throw Exception::invalidDatetime();
        }

        if (isset($data['orderDate']) === false || !$data['orderDate'] instanceof DateTime) {
            throw Exception::invalidOrderDate();
        }

        if (isset($data['reference']) === false || strlen($data['reference']) > 12) {
            throw Exception::invalidReference($data['reference']);
        }

        if (isset($data['language']) === false || strlen($data['language']) != 2) {
            throw Exception::invalidLanguage($data['language']);
        }

        if ($this->amountLeft + $this->amountRecovered + $this->amountToRecover !== $this->amount) {
            throw RecoveryException::invalidAmounts($this->amount, $this->amountToRecover, $this->amountRecovered, $this->amountLeft);
        }

        return true;
    }

    public function setStopRecurrence(): void
    {
        $this->stopRecurrence = 'OUI';
        $this->amountLeft = '0';
        $this->amountToRecover = '0';
    }


    /**
     * @param string $eptCode
     * @param string $companyCode
     * @param string $version
     * @return array
     */
    public function toArray(string $eptCode, string $companyCode, string $version): array
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

        if ($this->stopRecurrence) {
            $fields['stoprecurrence'] = $this->stopRecurrence;
        }

        return $fields;
    }
}
