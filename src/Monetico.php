<?php

namespace DansMaCulotte\Monetico;

use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Payment\Payment;
use DansMaCulotte\Monetico\Payment\PaymentResponse;

class Monetico
{
    const SERVICE_VERSION = '3.0';

    const MAIN_SERVICE_URL = 'https://p.monetico-services.com';
    const MISC_SERVICE_URL = 'https://payment-api.e-i.com';

    const PAYMENT_URI = 'paiement.cgi';
    const CAPTURE_URI = 'capture_paiement.cgi';
    const REFUND_URI = 'recredit_paiement.cgi';

    private $_eptCode = null;
    private $_securityKey = null;
    private $_companyCode = null;
    private $_returnUrl = null;
    private $_successUrl = null;
    private $_errorUrl = null;
    private $_debug = false;

    /**
     * Construct method
     *
     * @param string $eptCode EPT code
     * @param string $securityKey Security key
     * @param string $companyCode Company code
     * @param string $returnUrl Return url after payment process
     * @param string $successUrl Return url after successful payment
     * @param string $errorUrl Return url after errored payment
     *
     * @throws Exception
     */
    public function __construct($eptCode, $securityKey, $companyCode, $returnUrl, $successUrl, $errorUrl)
    {
        if (strlen($eptCode) != 7) {
            throw Exception::invalidEptCode($eptCode);
        }

        if (strlen($securityKey) != 40) {
            throw Exception::invalidSecurityKey();
        }

        $this->_eptCode = $eptCode;
        $this->_securityKey = self::getUsableKey($securityKey);
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
     * Transform security key for seal
     *
     * @param string $key
     *
     * @return string
     */
    public static function getUsableKey($key) {

        $hexStrKey = substr($key, 0, 38);
        $hexFinal = '' . substr($key, 38, 2) . '00';

        $cca0 = ord($hexFinal);

        if ($cca0 > 70 && $cca0 < 97) {
            $hexStrKey .= chr($cca0 - 23) . substr($hexFinal, 1, 1);
        } else {
            if (substr($hexFinal, 1, 1) == 'M') {
                $hexStrKey .= substr($hexFinal, 0, 1) . '0';
            } else {
                $hexStrKey .= substr($hexFinal, 0, 2);
            }
        }

        return pack('H*', $hexStrKey);
    }

    /**
     * Return payment url required to redirect on bank interface
     *
     * @param bool $debug
     *
     * @return string
     */
    public function getPaymentUrl($debug = false)
    {
        $mainServiceUrl = self::MAIN_SERVICE_URL;
        if ($this->_debug || $debug) {
            $mainServiceUrl .= '/test';
        }

        return $mainServiceUrl . '/' . self::PAYMENT_URI;
    }

    /**
     * Return array fields required on bank interface
     *
     * @param Payment $input
     *
     * @return array
     */
    public function getPaymentFields(Payment $input)
    {
        $seal = $input->generateSeal(
            $this->_eptCode,
            $this->_securityKey,
            self::SERVICE_VERSION,
            $this->_companyCode,
            $this->_returnUrl,
            $this->_successUrl,
            $this->_errorUrl
        );

        $fields = $input->generateFields(
            $this->_eptCode,
            $seal,
            self::SERVICE_VERSION,
            $this->_companyCode,
            $this->_returnUrl,
            $this->_successUrl,
            $this->_errorUrl
        );

        return $fields;
    }

    /**
     * Validate seal from response
     *
     * @param PaymentResponse $response
     *
     * @return bool
     */
    public function validateSeal(PaymentResponse $response)
    {
        $seal = $response->validateSeal(
            $this->_eptCode,
            $this->_securityKey,
            self::SERVICE_VERSION
        );

        return $seal;
    }

}
