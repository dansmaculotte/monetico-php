<?php

use DansMaCulotte\Monetico\Exceptions\PaymentException;
use DansMaCulotte\Monetico\Monetico;
use DansMaCulotte\Monetico\Payment\Response;
use DansMaCulotte\Monetico\Refund\Refund;
use PHPUnit\Framework\TestCase;
use DansMaCulotte\Monetico\Exceptions\Exception;

require_once 'Credentials.php';

class PaymentResponseTest extends TestCase
{
    private $data = array(
        'authentification' => 'ewogICAiZGV0YWlscyIgOiB7CiAgICAgICJQQVJlcyIgOiAiWSIsCiAgICAgICJWRVJlcyIgOiAiWSIsCiAgICAgICJzdGF0dXMzRFMiIDogMQogICB9LAogICAicHJvdG9jb2wiIDogIjNEU2VjdXJlIiwKICAgInN0YXR1cyIgOiAiYXV0aGVudGljYXRlZCIsCiAgICJ2ZXJzaW9uIiA6ICIxLjAuMiIKfQo=',
        'bincb' => '000003',
        'brand' => 'MC',
        'code-retour' => 'payetest',
        'cvx' => 'oui',
        'date' => '11/07/2019_a_10:51:19',
        'hpancb' => '07CDB0331260C06818027855F795C9F726585286',
        'ipclient' => '80.15.24.220',
        'MAC' => 'C3E8B0D0F71AABE041F50C240E1821E09CF9AACB',
        'modepaiement' => 'CB',
        'montant' => '42.42EUR',
        'numauto' => '000000',
        'originecb' => 'FRA',
        'originetr' => 'FRA',
        'reference' => 'D2345677',
        'texte-libre' => 'PHPUnit',
        'TPE' => '6784452',
        'vld' => '1219',
    );

    public function testPaymentResponseConstruct()
    {
        $response = new Response($this->data);
        $this->assertTrue($response instanceof Response);
    }

    public function testPaymentResponseMissingResponseKey()
    {
        $this->expectExceptionObject(Exception::missingResponseKey('TPE'));

        new Response(array());
    }

    public function testPaymentResponseExceptionDateTime()
    {
        $this->expectExceptionObject(Exception::invalidResponseDateTime());

        $data = $this->data;
        $data['date'] = 'oups';

        new Response($data);
    }


    public function testPaymentResponseExceptionReturnCode()
    {
        $this->expectExceptionObject(PaymentException::invalidResponseReturnCode('foo'));

        $data = $this->data;
        $data['code-retour'] = 'foo';

        new Response($data);
    }

    public function testPaymentResponseExceptionCardVerificationStatus()
    {
        $this->expectExceptionObject(PaymentException::invalidResponseCardVerificationStatus('nope'));

        $data = $this->data;
        $data['cvx'] = 'nope';

        new Response($data);
    }

    public function testPaymentResponseExceptionCardBrand()
    {
        $this->expectExceptionObject(PaymentException::invalidResponseCardBrand('foo'));

        $data = $this->data;
        $data['brand'] = 'foo';

        new Response($data);
    }

    public function testPaymentResponseExceptionRejectReason()
    {
        $this->expectExceptionObject(PaymentException::invalidResponseRejectReason('foobar'));

        $data = $this->data;
        $data['motifrefus'] = 'foobar';

        new Response($data);
    }

    public function testPaymentResponseExceptionPaymentMethod()
    {
        $this->expectExceptionObject(PaymentException::invalidResponsePaymentMethod('bar'));

        $data = $this->data;
        $data['modepaiement'] = 'bar';

        new Response($data);
    }

    public function testPaymentResponseExceptionFilteredReason()
    {
        $this->expectExceptionObject(PaymentException::invalidResponseFilteredReason('10'));

        $data = $this->data;
        $data['filtragecause'] = '10';

        new Response($data);
    }

    public function testPaymentWithOptionals()
    {
        $data = $this->data;

        $data['montantech'] = '50EUR';
        $data['filtragevaleur'] = 'foobar';
        $data['filtrage_etat'] = 'test';
        $data['cbenregistree'] = '1';
        $data['cbmasquee'] = '1234XXXXXXXXXXX1234';

        $response = new Response($data);

        $this->assertTrue($response->commitmentAmount === '50EUR');
        $this->assertTrue($response->filteredValue === 'foobar');
        $this->assertTrue($response->filteredStatus === 'test');
        $this->assertTrue($response->cardBookmarked === true);
        $this->assertTrue($response->cardMask === '1234XXXXXXXXXXX1234');
    }

    public function testSealIsValid()
    {
        $data = $this->data;

        $response = new Response($data);

        $sealIsValid = $response->validateSeal(
            EPT_CODE,
            Monetico::getUsableKey(SECURITY_KEY),
            3.0);

        $this->assertTrue($sealIsValid);

    }

    public function testAuthenticationDecode()
    {
        $data = $this->data;

        $response = new Response($data);

        $this->assertEquals('3DSecure', $response->authentication['protocol']);
        $this->assertEquals('authenticated', $response->authentication['status']);
        $this->assertEquals('1.0.2', $response->authentication['version']);
        $this->assertEquals('Y', $response->authentication['details']['PARes']);
        $this->assertEquals('Y', $response->authentication['details']['VERes']);
        $this->assertEquals('1', $response->authentication['details']['status3DS']);
    }
}