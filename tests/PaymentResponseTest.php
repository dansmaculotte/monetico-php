<?php

use DansMaCulotte\Monetico\Exceptions\PaymentException;
use DansMaCulotte\Monetico\Payment\Response;
use PHPUnit\Framework\TestCase;

class PaymentResponseTest extends TestCase
{
    private $data = [
        'TPE' => EPT_CODE,
        'date' => '01/01/2019_a_08:42:42',
        'amount' => '42.42EUR',
        'reference' => 'ABCDEF123',
        'MAC' => 'YOLO',
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

    public function testPaymentResponseConstruct()
    {
        $response = new Response($this->data);

        $this->assertTrue($response instanceof Response);
    }

    public function testPaymentResponseMissingResponseKey()
    {
        $this->expectExceptionObject(PaymentException::missingResponseKey('date'));

        new Response([]);
    }

    public function testPaymentResponseExceptionDateTime()
    {
        $this->expectExceptionObject(PaymentException::invalidDatetime());

        $data = $this->data;
        $data['date'] = 'oups';

        new Response($data);
    }

    public function testPaymentResponseExceptionReturnCode()
    {
        $this->expectExceptionObject(PaymentException::invalidReturnCode('foo'));

        $data = $this->data;
        $data['code-retour'] = 'foo';

        new Response($data);
    }

    public function testPaymentResponseExceptionCardVerificationStatus()
    {
        $this->expectExceptionObject(PaymentException::invalidCardVerificationStatus('nope'));

        $data = $this->data;
        $data['cvx'] = 'nope';

        new Response($data);
    }

    public function testPaymentResponseExceptionCardBrand()
    {
        $this->expectExceptionObject(PaymentException::invalidCardBrand('foo'));

        $data = $this->data;
        $data['brand'] = 'foo';

        new Response($data);
    }

    public function testPaymentResponseExceptionDDDSStatus()
    {
        $this->expectExceptionObject(PaymentException::invalidDDDSStatus('42'));

        $data = $this->data;
        $data['status3ds'] = '42';

        new Response($data);
    }

    public function testPaymentResponseExceptionRejectReason()
    {
        $this->expectExceptionObject(PaymentException::invalidRejectReason('foobar'));

        $data = $this->data;
        $data['motifrefus'] = 'foobar';

        new Response($data);
    }

    public function testPaymentResponseExceptionPaymentMethod()
    {
        $this->expectExceptionObject(PaymentException::invalidPaymentMethod('bar'));

        $data = $this->data;
        $data['modepaiement'] = 'bar';

        new Response($data);
    }

    public function testPaymentResponseExceptionFilteredReason()
    {
        $this->expectExceptionObject(PaymentException::invalidFilteredReason('10'));

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
}
