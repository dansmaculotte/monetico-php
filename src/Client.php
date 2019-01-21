<?php

namespace DansMaCulotte\Monetico;

use Carbon\Carbon;
use Carbon\Traits\Date;

class Client
{
    const SERVICE_VERSION = '3.0';

    const MAIN_SERVICE_URL = 'https://p.monetico-services.com';
    const MISC_SERVICE_URL = 'https://payment-api.e-i.com';

    const PAYMENT_URI = 'paiement.cgi';
    const CAPTURE_URI = 'capture_paiement.cgi';
    const REFUND_URI = 'recredit_paiement.cgi';

    const PAYMENT_WAYS = array(
        '1euro',
        '3xcb',
        '4xcb',
        'fivory',
        'paypal'
    );

    private $_eptCode = null;
    private $_securityKey = null;
    private $_companyCode = null;
    private $_returnUrl = null;
    private $_successUrl = null;
    private $_errorUrl = null;
    private $_options = array();
    private $_debug = false;

    /**
     * Construct method
     *
     * @param string $eptCode     EPT code
     * @param string $securityKey Security key
     * @param string $companyCode Company code
     * @param string $returnUrl   Return url after payment process
     * @param string $successUrl  Return url after successfull payment
     * @param string $errorUrl    Return url after errored payment
     *
     * @throws \Exception
     */
    public function __construct($eptCode, $securityKey, $companyCode, $returnUrl, $successUrl, $errorUrl)
    {
        if (strlen($eptCode) != 7) {
            throw new \Exception('EPT code is invalid, should be 7 characters long.');
        }

        if (strlen($securityKey) != 40) {
            throw new \Exception('Security key is invalid, should be 40 characters long.');
        }

        $this->_eptCode = $eptCode;
        $this->_securityKey = $securityKey;
        $this->_companyCode = $companyCode;
        $this->_returnUrl = $returnUrl;
        $this->_successUrl = $successUrl;
        $this->_errorUrl = $errorUrl;
    }

    /**
     * Set debug mode to activate test payment terminal
     *
     * @param boolean $value
     */
    public function setDebug($value = true) {
        $this->_debug = $value;
    }

    /**
     * Define card alias in case of an express payment
     * 
     * @param string $alias Alias card name
     */
    public function setCardAlias($alias)
    {
        $this->_options['aliascb'] = $alias;
    }

    /**
     * Force submission of card informations in case of an express payment
     * 
     * @param bool $value Enable or disable submission
     */
    public function setForceCard($value = true)
    {
        $this->_options['forcesaisiecb'] = ($value) ? '1' : '0';
    }

    /**
     * Bypass 3DSecure check
     * 
     * @param bool $value Enable or disable bypass
     */
    public function setDisable3DS($value = true)
    {
        $this->_options['3dsdebrayable'] = ($value) ? '1' : '0';
    }

    /**
     * Change company sign label on payment interface
     * 
     * @param string $label New sign label content
     */
    public function setSignLabel($label)
    {
        $this->_options['libelleMonetique'] = $label;
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

        $this->_options['desactivemoyenpaiement'] = join(',', $_ways);
    }

    /**
     * Generate seal to prepare payment
     * 
     * @param string $reference   Unique order reference
     * @param string $description Text area to include more informations
     * @param string $language    Language code e.g. FR, EN, ES ...
     * @param string $email       Customer email
     * @param string $amount      Amount without currency e.g. 6EUR, 10.55GBP 11USD ...
     * @param string $currency    Currency
     * @param string $datetime    Datetime (DD/MM/YYYY:HH:MM:SS)
     * @param array  $params      Options parameters
     * 
     * @return string
     */
    private function _generateSeal($reference, $description, $language, $email, $amount, $currency, $datetime, $params = array())
    {
        // <TPE>*<date>*<montant>*<reference>*<texte-libre>*<version>*<lgue>*<societe>*<mail>*
        $classicPaymentFormat = "%s*%s*%s*%s*%s*%s*%s*%s*%s*";
        $classicPaymentOutput = sprintf(
            $classicPaymentFormat,
            $this->_eptCode,
            $datetime,
            $amount . $currency,
            $reference,
            $description,
            self::SERVICE_VERSION,
            $language,
            $this->_companyCode,
            $email
        );

        $commitments = $params['commitments'];
        $commitmentsCount = count($commitments);

        // <nbrech>*<dateech1>*<montantech1>*<dateech2>*<montantech2>*<dateech3>*<montantech3>*<dateech4>*<montantech4>*<options>
        $splitedPaymentFormat = "%s*%s*%s*%s*%s*%s*%s*%s*%s*%s";
        $splitedPaymentOuput = sprintf(
            $splitedPaymentFormat,
            ($commitmentsCount > 0) ? $commitmentsCount : '',
            ($commitmentsCount >= 1) ? $commitments[0]['date'] : '',
            ($commitmentsCount >= 1) ? $commitments[0]['amount'] : '',
            ($commitmentsCount >= 2) ? $commitments[1]['date'] : '',
            ($commitmentsCount >= 2) ? $commitments[1]['amount'] : '',
            ($commitmentsCount >= 3) ? $commitments[2]['date'] : '',
            ($commitmentsCount >= 3) ? $commitments[2]['amount'] : '',
            ($commitmentsCount >= 4) ? $commitments[3]['date'] : '',
            ($commitmentsCount >= 4) ? $commitments[3]['amount'] : '',
            http_build_query($params['options'])
        );

        return strtolower(
            hash_hmac(
                'sha1',
                $classicPaymentOutput . $splitedPaymentOuput,
                $this->_securityKey
            )
        );
    }

    /**
     * Generate payload to return to customer
     * 
     * @param string $reference   Unique order reference
     * @param string $description Text area to include more informations
     * @param string $language    Language code e.g. FR, EN, ES ...
     * @param string $email       Customer email
     * @param float  $amount      Amount order
     * @param string $currency    Payment currency
     * @param Date   $datetime    DateTime class object
     * @param array  $commitments Payment steps if defined
     * @param array  $options     Optional parameters
     *
     * @return array
     */
    public function generatePayload($reference, $description, $language, $email, $amount, $currency, $datetime, $commitments = array(), $options = array())
    {
        $datetime = $datetime->format('d/m/Y:H:i:s');

        $seal = $this->_generateSeal(
            $reference,
            $description,
            $language,
            $email,
            $amount,
            $currency,
            $datetime,
            array(
                'options' => array_merge(
                    $this->_options,
                    $options
                ),
                'commitments' => $commitments,
            )
        );

        $commitmentsCount = count($commitments);
        $_submitCommitments = array();

        if ($commitmentsCount > 0) {
            $_submitCommitments['nbrech'] = $commitmentsCount;

            if ($commitmentsCount >= 1) {
                $_submitCommitments['dateech1'] = $commitments[0]['date'];
                $_submitCommitments['montantech1'] = $commitments[0]['amount'];
            }

            if ($commitmentsCount >= 2) {
                $_submitCommitments['dateech2'] = $commitments[1]['date'];
                $_submitCommitments['montantech2'] = $commitments[1]['amount'];
            }

            if ($commitmentsCount >= 3) {
                $_submitCommitments['dateech3'] = $commitments[2]['date'];
                $_submitCommitments['montantech3'] = $commitments[2]['amount'];
            }

            if ($commitmentsCount >= 4) {
                $_submitCommitments['dateech4'] = $commitments[3]['date'];
                $_submitCommitments['montantech4'] = $commitments[3]['amount'];
            }
        }

        $mainServiceUrl = self::MAIN_SERVICE_URL;
        if ($this->_debug) {
            $mainServiceUrl .= '/test';
        }

        return array(
            'action' => $mainServiceUrl . '/' . self::PAYMENT_URI,
            'fields' => array_merge(
                array(
                    'version' => self::SERVICE_VERSION,
                    'TPE' => $this->_eptCode,
                    'date' => $datetime,
                    'montant' => $amount . $currency,
                    'reference' => $reference,
                    'MAC' => $seal,
                    'url_retour' => $this->_returnUrl,
                    'url_retour_ok' => $this->_successUrl,
                    'url_retour_err' => $this->_errorUrl,
                    'lgue' => $language,
                    'societe' => $this->_companyCode,
                    'texte-libre' => $description,
                    'mail' => $email,
                ),
                $_submitCommitments
            )
        );
    }

    /**
     * @param array $payload
     */
    public function parsePaymentReturn($payload)
    {
        $seal = (isset($payload['MAC']) ? $payload['MAC'] : null);
        $date = (isset($payload['date']) ? Carbon::createFromFormat('d/m/Y O H:i:s', $payload['date']) : null);
        $eptCode = (isset($payload['TPE']) ? $payload['TPE'] : null);
        $amount = (isset($payload['montant']) ? $payload['montant'] : null);
        $reference = (isset($payload['reference']) ? $payload['reference'] : null);
        $text = (isset($payload['texte-libre']) ? $payload['texte-libre'] : null);
        $returnCode = (isset($payload['code-retour']) ? $payload['code-retour'] : null);
        $cardVerificationStatus = (isset($payload['cvx']) ? $payload['cvx'] : null);
        $cardExpirationDate = (isset($payload['vld']) ? $payload['vld'] : null);
        $cardBrand = (isset($payload['brand']) ? $payload['brand'] : null);
        $DDDSStatus = (isset($payload['status3ds']) ? $payload['status3ds'] : null);
        $authNumber = (isset($payload['numauto']) ? $payload['numauto'] : null);
        $rejectReason = (isset($payload['motifrefus']) ? $payload['motifrefus'] : null);
        $cardCountry = (isset($payload['originecb']) ? $payload['originecb'] : null);
        $cardBIN = (isset($payload['bincb']) ? $payload['bincb'] : null);
        $cardHash = (isset($payload['hpancb']) ? $payload['hpancb'] : null);
        $clientIp = (isset($payload['ipclient']) ? $payload['ipclient'] : null);
        $transactionCountry = (isset($payload['originetr']) ? $payload['originetr'] : null);
        $veresStatus = (isset($payload['veres']) ? $payload['veres'] : null);
        $paresStatus = (isset($payload['pares']) ? $payload['pares'] : null);
        $commitmentAmount = (isset($payload['montantech']) ? $payload['montantech'] : null);
        $filteredReason = (isset($payload['filtragecause']) ? $payload['filtragecause'] : null);
        $filteredValue = (isset($payload['filtragevaleur']) ? $payload['filtragevaleur'] : null);
        $cardBookmarked = (isset($payload['cbenregistree']) ? $payload['cbenregistree'] : null);
        $cardMask = (isset($payload['cbmasquee']) ? $payload['cbmasquee'] : null);
        $paymentMethod = (isset($payload['modepaiement']) ? $payload['modepaiement'] : null);
    }
}
