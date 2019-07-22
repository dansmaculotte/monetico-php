<?php

use \DansMaCulotte\Monetico\Exceptions\Exception;
use Carbon\Carbon;
use DansMaCulotte\Monetico\Monetico;
use DansMaCulotte\Monetico\Payment\Payment;
use DansMaCulotte\Monetico\Payment\Response;
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

        $payment = new Payment([
            'reference' => 'ABCDEF123',
            'description' => 'PHPUnit',
            'language' => 'FR',
            'email' => 'john@english.fr',
            'amount' => 42.42,
            'currency' => 'EUR',
            'datetime' => Carbon::create(2019, 1, 1),
        ]);

        $fields = $monetico->getPaymentFields($payment);

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

        $data = [
            'TPE' => EPT_CODE,
            'date' => '01/01/2019_a_08:42:42',
            'amount' => '42.42EUR',
            'reference' => 'ABCDEF123',
            'texte-libre' => 'PHPUnit',
            'version' => '3.0',
            'code-retour' => 'payetest',
            'cvx' => 'oui',
            'vld' => '1219',
            'brand' => 'VI',
            'status3ds' => '4',
            'numauto' => '000000',
            'motifrefus' => null,
            'originecb' => 'FRA',
            'bincb' => '000000',
            'hpancb' => 'NOPE',
            'ipclient' => '127.0.0.1',
            'originetr' => 'FRA',
            'veres' => null,
            'pares' => null,
        ];

        $output = vsprintf(
            '%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*',
            $data
        );

        $seal = strtolower(
            hash_hmac(
                'sha1',
                $output,
                Monetico::getUsableKey(SECURITY_KEY)
            )
        );

        $data['MAC'] = $seal;

        $response = new Response($data);

        $isValid = $monetico->validateSeal($response);
        $this->assertTrue($isValid);
    }
}
