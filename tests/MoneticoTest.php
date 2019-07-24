<?php

use \DansMaCulotte\Monetico\Exceptions\Exception;
use Carbon\Carbon;
use DansMaCulotte\Monetico\Cancel\Cancel;
use DansMaCulotte\Monetico\Monetico;
use DansMaCulotte\Monetico\Payment\Payment;
use DansMaCulotte\Monetico\Payment\Response;
use DansMaCulotte\Monetico\Recovery\Recovery;
use DansMaCulotte\Monetico\Refund\Refund;
use PHPUnit\Framework\TestCase;

require_once 'Credentials.fake.php';

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

    public function testMoneticoRecoveryUrl()
    {
        $monetico = new Monetico(
            EPT_CODE,
            SECURITY_KEY,
            COMPANY_CODE,
            RETURN_URL,
            RETURN_SUCCESS_URL,
            RETURN_ERROR_URL
        );

        $url = $monetico->getRecoveryUrl();

        $this->assertTrue($url === 'https://p.monetico-services.com/capture_paiement.cgi');

        $url = $monetico->getRecoveryUrl(true);

        $this->assertTrue($url === 'https://p.monetico-services.com/test/capture_paiement.cgi');
    }

    public function testMoneticoRefundUrl()
    {
        $monetico = new Monetico(
            EPT_CODE,
            SECURITY_KEY,
            COMPANY_CODE,
            RETURN_URL,
            RETURN_SUCCESS_URL,
            RETURN_ERROR_URL
        );

        $url = $monetico->getRefundUrl();

        $this->assertTrue($url === 'https://p.monetico-services.com/recredit_paiement.cgi');

        $url = $monetico->getRefundUrl(true);

        $this->assertTrue($url === 'https://p.monetico-services.com/test/recredit_paiement.cgi');
    }

    public function testMoneticoCancelUrl()
    {
        $monetico = new Monetico(
            EPT_CODE,
            SECURITY_KEY,
            COMPANY_CODE,
            RETURN_URL,
            RETURN_SUCCESS_URL,
            RETURN_ERROR_URL
        );

        $url = $monetico->getCancelUrl();

        $this->assertTrue($url === 'https://p.monetico-services.com/capture_paiement.cgi');

        $url = $monetico->getCancelUrl(true);

        $this->assertTrue($url === 'https://p.monetico-services.com/test/capture_paiement.cgi');
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
            'reference' => 'AYCDEF123',
            'description' => 'PHPUnit',
            'language' => 'FR',
            'email' => 'john@english.fr',
            'amount' => 42.42,
            'currency' => 'EUR',
            'dateTime' => Carbon::create(2019, 7, 17),
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
        ];

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

        $response = new Response($data);

        $isValid = $monetico->validateSeal($response);
        $this->assertTrue($isValid);
    }

    public function testMoneticoRecoveryFields()
    {
        $monetico = new Monetico(
            EPT_CODE,
            SECURITY_KEY,
            COMPANY_CODE,
            RETURN_URL,
            RETURN_SUCCESS_URL,
            RETURN_ERROR_URL
        );

        $recovery = new Recovery([
            'reference' => 'AXCDEF123',
            'language' => 'FR',
            'amount' => 42.42,
            'amountToRecover' => 0,
            'amountRecovered' => 0,
            'amountLeft' => 42.42,
            'currency' => 'EUR',
            'orderDate' => Carbon::create(2019, 07, 17),
            'dateTime' => Carbon::create(2019, 07, 17),
        ]);

        $recovery->setFileNumber('ABC');
        $recovery->setInvoiceType('preauto');
        $recovery->setPhone();
        $recovery->setStopRecurrence();

        $fields = $monetico->getRecoveryFields($recovery);

        $this->assertIsArray($fields);
        $this->assertArrayHasKey('version', $fields);
        $this->assertArrayHasKey('TPE', $fields);
        $this->assertArrayHasKey('date', $fields);
        $this->assertArrayHasKey('date_commande', $fields);
        $this->assertArrayHasKey('reference', $fields);
        $this->assertArrayHasKey('MAC', $fields);
        $this->assertArrayHasKey('lgue', $fields);
        $this->assertArrayHasKey('societe', $fields);
        $this->assertArrayHasKey('montant', $fields);
        $this->assertArrayHasKey('montant_a_capturer', $fields);
        $this->assertArrayHasKey('montant_deja_capture', $fields);
        $this->assertArrayHasKey('montant_restant', $fields);
        $this->assertArrayHasKey('stoprecurrence', $fields);
        $this->assertArrayHasKey('numero_dossier', $fields);
        $this->assertArrayHasKey('facture', $fields);
        $this->assertArrayHasKey('phonie', $fields);
    }

    public function testMoneticoCancelFields()
    {
        $monetico = new Monetico(
            EPT_CODE,
            SECURITY_KEY,
            COMPANY_CODE,
            RETURN_URL,
            RETURN_SUCCESS_URL,
            RETURN_ERROR_URL
        );

        $cancel = new Cancel([
            'reference' => 'AXCDEF123',
            'language' => 'FR',
            'amount' => 42.42,
            'amountRecovered' => 0,
            'currency' => 'EUR',
            'orderDate' => Carbon::create(2019, 07, 17),
            'dateTime' => Carbon::create(2019, 07, 17),
        ]);

        $fields = $monetico->getCancelFields($cancel);

        $this->assertIsArray($fields);
        $this->assertArrayHasKey('version', $fields);
        $this->assertArrayHasKey('TPE', $fields);
        $this->assertArrayHasKey('date', $fields);
        $this->assertArrayHasKey('date_commande', $fields);
        $this->assertArrayHasKey('reference', $fields);
        $this->assertArrayHasKey('MAC', $fields);
        $this->assertArrayHasKey('lgue', $fields);
        $this->assertArrayHasKey('societe', $fields);
        $this->assertArrayHasKey('montant', $fields);
        $this->assertArrayHasKey('montant_a_capturer', $fields);
        $this->assertArrayHasKey('montant_deja_capture', $fields);
        $this->assertArrayHasKey('montant_restant', $fields);
    }

    public function testMoneticoRefundFields()
    {
        $monetico = new Monetico(
            EPT_CODE,
            SECURITY_KEY,
            COMPANY_CODE,
            RETURN_URL,
            RETURN_SUCCESS_URL,
            RETURN_ERROR_URL
        );

        $refund = new Refund([
            'datetime' => Carbon::create(2019, 2, 1),
            'orderDatetime' => Carbon::create(2019, 1, 1),
            'recoveryDatetime' => Carbon::create(2019, 1, 1),
            'authorizationNumber' => '1222',
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'refundAmount' => 50,
            'maxRefundAmount' => 80,
        ]);

        $refund->setInvoiceType('preauto');
        $refund->setFileNumber('ABC');

        $fields = $monetico->getRefundFields($refund);

        $this->assertIsArray($fields);
        $this->assertArrayHasKey('version', $fields);
        $this->assertArrayHasKey('TPE', $fields);
        $this->assertArrayHasKey('date', $fields);
        $this->assertArrayHasKey('date_commande', $fields);
        $this->assertArrayHasKey('date_remise', $fields);
        $this->assertArrayHasKey('num_autorisation', $fields);
        $this->assertArrayHasKey('reference', $fields);
        $this->assertArrayHasKey('MAC', $fields);
        $this->assertArrayHasKey('lgue', $fields);
        $this->assertArrayHasKey('societe', $fields);
        $this->assertArrayHasKey('montant', $fields);
        $this->assertArrayHasKey('montant_recredit', $fields);
        $this->assertArrayHasKey('montant_possible', $fields);
        $this->assertArrayHasKey('facture', $fields);
        $this->assertArrayHasKey('numero_dossier', $fields);
    }
}
