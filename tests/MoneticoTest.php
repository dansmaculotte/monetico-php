<?php

use \DansMaCulotte\Monetico\Exceptions\Exception;
use Carbon\Carbon;
use DansMaCulotte\Monetico\Monetico;
use DansMaCulotte\Monetico\Requests\CancelRequest;
use DansMaCulotte\Monetico\Requests\PurchaseRequest;
use DansMaCulotte\Monetico\Requests\RecoveryRequest;
use DansMaCulotte\Monetico\Requests\RefundRequest;
use DansMaCulotte\Monetico\Responses\PurchaseResponse;
use PHPUnit\Framework\TestCase;

require_once 'Credentials.fake.php';

class MoneticoTest extends TestCase
{
    public function testMoneticoInstance()
    {
        $monetico = new Monetico(
            EPT_CODE,
            SECURITY_KEY,
            COMPANY_CODE
        );

        $this->assertInstanceOf(Monetico::class, $monetico);
    }

    public function testMoneticoExceptionEptCode()
    {
        $this->expectExceptionObject(Exception::invalidEptCode('error'));

        new Monetico(
            'error',
            SECURITY_KEY,
            COMPANY_CODE
        );
    }

    public function testMoneticoExceptionSecurityCode()
    {
        $this->expectExceptionObject(Exception::invalidSecurityKey());

        new Monetico(
            EPT_CODE,
            'error',
            COMPANY_CODE
        );
    }

    public function testMoneticoPaymentFields()
    {
        $monetico = new Monetico(
            EPT_CODE,
            SECURITY_KEY,
            COMPANY_CODE
        );

        $capture = new PurchaseRequest([
            'reference' => 'AYCDEF123',
            'description' => 'PHPUnit',
            'language' => 'FR',
            'email' => 'john@english.fr',
            'amount' => 42.42,
            'currency' => 'EUR',
            'dateTime' => Carbon::create(2019, 7, 17),
            'successUrl' => 'https://127.0.0.1/success',
            'errorUrl' => 'https://127.0.0.1/error'
        ]);

        $fields = $monetico->getFields($capture);

        $this->assertIsArray($fields);
        $this->assertArrayHasKey('version', $fields);
        $this->assertArrayHasKey('TPE', $fields);
        $this->assertArrayHasKey('date', $fields);
        $this->assertArrayHasKey('montant', $fields);
        $this->assertArrayHasKey('reference', $fields);
        $this->assertArrayHasKey('MAC', $fields);
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
            COMPANY_CODE
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
            'usage' => 'credit',
            'typecompte' => 'particulier',
            'ecard' => 'non',
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

        $response = new PurchaseResponse($data);

        $isValid = $monetico->validate($response);
        $this->assertTrue($isValid);
    }

    public function testMoneticoRecoveryFields()
    {
        $monetico = new Monetico(
            EPT_CODE,
            SECURITY_KEY,
            COMPANY_CODE
        );

        $recovery = new RecoveryRequest([
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

        $fields = $monetico->getFields($recovery);

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
            COMPANY_CODE
        );

        $cancel = new CancelRequest([
            'reference' => 'AXCDEF123',
            'language' => 'FR',
            'amount' => 42.42,
            'amountRecovered' => 0,
            'currency' => 'EUR',
            'orderDate' => Carbon::create(2019, 07, 17),
            'dateTime' => Carbon::create(2019, 07, 17),
        ]);

        $fields = $monetico->getFields($cancel);

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
            COMPANY_CODE
        );

        $refund = new RefundRequest([
            'dateTime' => Carbon::create(2019, 2, 1),
            'orderDate' => Carbon::create(2019, 1, 1),
            'recoveryDate' => Carbon::create(2019, 1, 1),
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

        $fields = $monetico->getFields($refund);

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
