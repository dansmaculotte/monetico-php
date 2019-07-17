<?php

use Carbon\Carbon;
use DansMaCulotte\Monetico\Capture\Capture;
use DansMaCulotte\Monetico\Monetico;
use \DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Payment\Payment;
use DansMaCulotte\Monetico\Payment\PaymentResponse;
use PHPUnit\Framework\TestCase;

require_once 'Credentials.php';

class MoneticoTest extends TestCase
{
    public function testMoneticoInstance()
    {
        $monetico = new Monetico(
            EPT_CODE,
            SECURITY_KEY,
            COMPANY_CODE,
            RETURN_URL,
            RETURN_SUCCESS_URL,
            RETURN_ERROR_URL
        );

        $payment = new Payment(array(
            'reference' => 'ABCDEF123',
            'description' => 'PHPUnit',
            'language' => 'FR',
            'email' => 'john@english.fr',
            'amount' => 42.42,
            'currency' => 'EUR',
            'datetime' => Carbon::create(2019, 1, 1),
        ));

        $this->assertTrue($monetico instanceof Monetico);
    }

    public function testMoneticoExceptionEptCode()
    {
        $this->expectExceptionObject(Exception::invalidEptCode('error'));

        new Monetico(
            'error',
            SECURITY_KEY,
            COMPANY_CODE,
            RETURN_URL,
            RETURN_SUCCESS_URL,
            RETURN_ERROR_URL
        );
    }

    public function testMoneticoExceptionSecurityCode()
    {
        $this->expectExceptionObject(Exception::invalidSecurityKey());

        new Monetico(
            EPT_CODE,
            'error',
            COMPANY_CODE,
            RETURN_URL,
            RETURN_SUCCESS_URL,
            RETURN_ERROR_URL
        );
    }

    public function testMoneticoPaymentUrl()
    {
        $monetico = new Monetico(
            EPT_CODE,
            SECURITY_KEY,
            COMPANY_CODE,
            RETURN_URL,
            RETURN_SUCCESS_URL,
            RETURN_ERROR_URL
        );

        $url = $monetico->getPaymentUrl();

        $this->assertTrue($url === 'https://p.monetico-services.com/paiement.cgi');

        $url = $monetico->getPaymentUrl(true);

        $this->assertTrue($url === 'https://p.monetico-services.com/test/paiement.cgi');
    }

    public function testMoneticoDebugMode()
    {
        $monetico = new Monetico(
            EPT_CODE,
            SECURITY_KEY,
            COMPANY_CODE,
            RETURN_URL,
            RETURN_SUCCESS_URL,
            RETURN_ERROR_URL
        );

        $monetico->setDebug();

        $url = $monetico->getPaymentUrl(true);

        $this->assertTrue($url === 'https://p.monetico-services.com/test/paiement.cgi');
    }

    public function testMoneticoPaymentFields()
    {
        $monetico = new Monetico(
            EPT_CODE,
            SECURITY_KEY,
            COMPANY_CODE,
            RETURN_URL,
            RETURN_SUCCESS_URL,
            RETURN_ERROR_URL
        );

        $payment = new Payment(array(
            'reference' => 'AYCDEF123',
            'description' => 'PHPUnit',
            'language' => 'FR',
            'email' => 'john@english.fr',
            'amount' => 42.42,
            'currency' => 'EUR',
            'datetime' => Carbon::create(2019, 7, 17),
        ));

        $fields = $monetico->getPaymentFields($payment);
        print_r($fields);

        $this->assertIsArray($fields);
        $this->assertArrayHasKey('version', $fields);
        $this->assertArrayHasKey('TPE', $fields);
        $this->assertArrayHasKey('date', $fields);
        $this->assertArrayHasKey('montant', $fields);
        $this->assertArrayHasKey('reference', $fields);
        $this->assertArrayHasKey('MAC', $fields);
        $this->assertArrayHasKey('url_retour', $fields);
        $this->assertArrayHasKey('url_retour_ok', $fields);
        $this->assertArrayHasKey('url_retour_err', $fields);
        $this->assertArrayHasKey('lgue', $fields);
        $this->assertArrayHasKey('societe', $fields);
        $this->assertArrayHasKey('texte-libre', $fields);
        $this->assertArrayHasKey('mail', $fields);
    }

    public function testMoneticoValidateSeal()
    {
        $monetico = new Monetico(
            EPT_CODE,
            SECURITY_KEY,
            COMPANY_CODE,
            RETURN_URL,
            RETURN_SUCCESS_URL,
            RETURN_ERROR_URL
        );

        $data = array(
            'TPE' => EPT_CODE,
            'authentification' => 'ewogICAiZGV0YWlscyIgOiB7CiAgICAgICJQQVJlcyIgOiAiWSIsCiAgICAgICJWRVJlcyIgOiAiWSIsCiAgICAgICJzdGF0dXMzRFMiIDogMQogICB9LAogICAicHJvdG9jb2wiIDogIjNEU2VjdXJlIiwKICAgInN0YXR1cyIgOiAiYXV0aGVudGljYXRlZCIsCiAgICJ2ZXJzaW9uIiA6ICIxLjAuMiIKfQo=',
            'bincb' => '000003',
            'brand' => 'MC',
            'code-retour' => 'payetest',
            'cvx' => 'oui',
            'date' => '11/07/2019_a_10:51:19',
            'hpancb' => '07CDB0331260C06818027855F795C9F726585286',
            'ipclient' => '80.15.24.220',
            'modepaiement' => 'CB',
            'montant' => '42.42EUR',
            'numauto' => '000000',
            'originecb' => 'FRA',
            'originetr' => 'FRA',
            'reference' => 'D2345677',
            'texte-libre' => 'PHPUnit',
            'vld' => '1219',
        );

        ksort($data);
        $output = urldecode(http_build_query($data, null, '*'));

        $seal = strtoupper(
            hash_hmac(
                'sha1',
                $output,
                Monetico::getUsableKey(SECURITY_KEY)
            )
        );

        $data['MAC'] = $seal;

        $response = new PaymentResponse($data);

        $isValid = $monetico->validateSeal($response);
        $this->assertTrue($isValid);
    }

    public function testMoneticoCaptureFields()
    {
        $monetico = new Monetico(
            EPT_CODE,
            SECURITY_KEY,
            COMPANY_CODE,
            RETURN_URL,
            RETURN_SUCCESS_URL,
            RETURN_ERROR_URL
        );

        $payment = new Capture(array(
            'reference' => 'AXCDEF123',
            'language' => 'FR',
            'amount' => 42.42,
            'amountToCapture' => 0,
            'amountCaptured' => 0,
            'amountLeft' => 0,
            'currency' => 'EUR',
            'orderDatetime' => Carbon::create(2019, 07, 17),
            'datetime' => Carbon::create(2019, 07, 17),
        ));

        $fields = $monetico->getCaptureFields($payment);

        print_r($fields);

        $this->assertIsArray($fields);
        $this->assertArrayHasKey('version', $fields);
        $this->assertArrayHasKey('TPE', $fields);
        $this->assertArrayHasKey('date', $fields);
        $this->assertArrayHasKey('montant', $fields);
        $this->assertArrayHasKey('reference', $fields);
        $this->assertArrayHasKey('MAC', $fields);
        $this->assertArrayHasKey('lgue', $fields);
        $this->assertArrayHasKey('societe', $fields);
    }
}