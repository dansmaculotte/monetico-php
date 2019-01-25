<?php

namespace DansMaCulotte\Monetico\Payment;

use DansMaCulotte\Monetico\Exceptions\PaymentException;

class Payment
{
    /** @var string */
    public $reference;

    /** @var string */
    public $description;

    /** @var string */
    public $language;

    /** @var string */
    public $email;

    /** @var float */
    public $amount;

    /** @var string */
    public $currency;

    /** @var \DateTime */
    public $datetime;

    /** @var array */
    public $options;

    /** @var array */
    public $commitments;

    /** @var string */
    const FORMAT_OUTPUT = '%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s';

    /** @var array */
    const PAYMENT_WAYS = array(
        '1euro',
        '3xcb',
        '4xcb',
        'fivory',
        'paypal'
    );

    /**
     * InputPayload constructor.
     *
     * @param array $data
     * @param array $commitments
     * @param array $options
     *
     * @throws PaymentException
     */
    public function __construct($data = array(), $commitments = array(), $options = array())
    {
        $this->reference = $data['reference'];
        if (strlen($this->reference) > 12) {
            throw PaymentException::invalidReference($this->reference);
        }

        $this->language = $data['language'];
        if (strlen($this->language) != 2) {
            throw PaymentException::invalidLanguage($this->language);
        }

        $this->datetime = $data['datetime'];
        if (!is_a($this->datetime, 'DateTime')) {
            throw PaymentException::invalidDatetime();
        }

        $this->description = $data['description'];
        $this->email = $data['email'];
        $this->amount = $data['amount'];
        $this->currency = $data['currency'];

        $this->options = $options;
        $this->commitments = $commitments;
    }

    /**
     * Define card alias in case of an express payment
     *
     * @param string $alias Alias card name
     */
    public function setCardAlias($alias)
    {
        $this->options['aliascb'] = $alias;
    }

    /**
     * Force submission of card informations in case of an express payment
     *
     * @param bool $value Enable or disable submission
     */
    public function setForceCard($value = true)
    {
        $this->options['forcesaisiecb'] = ($value) ? '1' : '0';
    }

    /**
     * Bypass 3DSecure check
     *
     * @param bool $value Enable or disable bypass
     */
    public function setDisable3DS($value = true)
    {
        $this->options['3dsdebrayable'] = ($value) ? '1' : '0';
    }

    /**
     * Change company sign label on payment interface
     *
     * @param string $label New sign label content
     */
    public function setSignLabel($label)
    {
        $this->options['libelleMonetique'] = $label;
    }

    /**
     * Disable ways of payment on payment interface
     *
     * @param array[string] $ways List of payment ways to disable
     */
    public function setDisabledPaymentWays($ways = array())
    {
        $_ways = array();

        foreach ($ways as $way) {
            if (in_array($way, self::PAYMENT_WAYS)) {
                array_push($_ways, $way);
            }
        }

        $this->options['desactivemoyenpaiement'] = join(',', $_ways);
    }

    /**
     * Generate seal to prepare payment
     *
     * @param string $eptCode
     * @param string $securityKey
     * @param string $version
     * @param string $companyCode
     *
     * @return string
     */
    public function generateSeal($eptCode, $securityKey, $version, $companyCode)
    {
        $commitments = $this->commitments;
        $commitmentsCount = count($commitments);

        $output = sprintf(
            self::FORMAT_OUTPUT,
            $eptCode,
            $this->datetime->format('d/m/Y:H:i:s'),
            $this->amount . $this->currency,
            $this->reference,
            $this->description,
            $version,
            $this->language,
            $companyCode,
            $this->email,
            ($commitmentsCount > 0) ? $commitmentsCount : '',
            ($commitmentsCount >= 1) ? $commitments[0]['date'] : '',
            ($commitmentsCount >= 1) ? $commitments[0]['amount'] : '',
            ($commitmentsCount >= 2) ? $commitments[1]['date'] : '',
            ($commitmentsCount >= 2) ? $commitments[1]['amount'] : '',
            ($commitmentsCount >= 3) ? $commitments[2]['date'] : '',
            ($commitmentsCount >= 3) ? $commitments[2]['amount'] : '',
            ($commitmentsCount >= 4) ? $commitments[3]['date'] : '',
            ($commitmentsCount >= 4) ? $commitments[3]['amount'] : '',
            http_build_query($this->options)
        );

        return strtolower(
            hash_hmac(
                'sha1',
                $output,
                $securityKey
            )
        );
    }

    /**
     * @param string $eptCode
     * @param string $seal
     * @param string $version
     * @param string $companyCode
     * @param string $returnUrl
     * @param string $successUrl
     * @param string $errorUrl
     *
     * @return array
     */
    public function generateFields($eptCode, $seal, $version, $companyCode, $returnUrl, $successUrl, $errorUrl)
    {
        $commitmentsCount = count($this->commitments);
        $_submitCommitments = array();

        if ($commitmentsCount > 0) {
            $_submitCommitments['nbrech'] = $commitmentsCount;

            if ($commitmentsCount >= 1) {
                $_submitCommitments['dateech1'] = $this->commitments[0]['date'];
                $_submitCommitments['montantech1'] = $this->commitments[0]['amount'];
            }

            if ($commitmentsCount >= 2) {
                $_submitCommitments['dateech2'] = $this->commitments[1]['date'];
                $_submitCommitments['montantech2'] = $this->commitments[1]['amount'];
            }

            if ($commitmentsCount >= 3) {
                $_submitCommitments['dateech3'] = $this->commitments[2]['date'];
                $_submitCommitments['montantech3'] = $this->commitments[2]['amount'];
            }

            if ($commitmentsCount >= 4) {
                $_submitCommitments['dateech4'] = $this->commitments[3]['date'];
                $_submitCommitments['montantech4'] = $this->commitments[3]['amount'];
            }
        }

        return array_merge(
            array(
                'version' => $version,
                'TPE' => $eptCode,
                'date' => $this->datetime->format('d/m/Y:H:i:s'),
                'montant' => $this->amount . $this->currency,
                'reference' => $this->reference,
                'MAC' => $seal,
                'url_retour' => $returnUrl,
                'url_retour_ok' => $successUrl . '?reference=' . $this->reference,
                'url_retour_err' => $errorUrl . '?reference=' . $this->reference,
                'lgue' => $this->language,
                'societe' => $companyCode,
                'texte-libre' => $this->description,
                'mail' => $this->email,
            ),
            $_submitCommitments
        );
    }
}