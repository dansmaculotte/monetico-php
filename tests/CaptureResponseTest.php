<?php

use DansMaCulotte\Monetico\Capture\CaptureResponse;
use DansMaCulotte\Monetico\Exceptions\CaptureException;
use PHPUnit\Framework\TestCase;

class CaptureResponseTest extends TestCase
{
    public function testCaptureResponseConstruct()
    {
        $response = new CaptureResponse([
            'version' => '1.0',
            'reference' => '000000000145',
            'cdr' => '1',
            'lib' => 'paiement accepte',
            'aut' => '123456',
        ]);

        $this->assertTrue($response instanceof CaptureResponse);
    }

    public function testCaptureResponseWithAuthorization()
    {
        $response = new CaptureResponse([
            'version' => '1.0',
            'reference' => '000000000145',
            'cdr' => '1',
            'lib' => 'paiement accepte',
            'aut' => '123456',
            'montant_estime' => '10EUR ',
            'date_autorisation' => '2019-05-20 ',
            'montant_debite' => '5EUR ',
            'date_debit' => '2019-05-30 ',
            'numero_dossier' => 'doss123456 ',
            'type_facture' => 'preauto',
        ]);

        $this->assertTrue($response instanceof CaptureResponse);
    }

    public function testCaptureResponseInvalidReferenceException()
    {
        $this->expectExceptionObject(CaptureException::invalidReference('thisisawrongreference'));

        $response = new CaptureResponse([
            'version' => '1.0',
            'reference' => 'thisisawrongreference',
            'cdr' => '1',
            'lib' => 'paiement accepte',
            'aut' => '123456',
        ]);

        $this->assertTrue($response instanceof CaptureResponse);
    }

    public function testCaptureResponseExceptionInvalidReference()
    {
        $this->expectExceptionObject(CaptureException::invalidReference('thisisawrongreference'));

        new CaptureResponse([
            'version' => '1.0',
            'reference' => 'thisisawrongreference',
            'cdr' => '1',
            'lib' => 'paiement accepte',
            'aut' => '123456',
        ]);

    }

    public function testCaptureResponseExceptionInvalidAuthDatetime()
    {
        $this->expectExceptionObject(CaptureException::invalidDatetime());

        new CaptureResponse([
            'version' => '1.0',
            'reference' => 'ABCD123',
            'cdr' => '1',
            'lib' => 'paiement accepte',
            'aut' => '123456',
            'date_autorisation' => 'juin 2019'
        ]);

    }

    public function testCaptureResponseExceptionInvalidDebitDatetime()
    {
        $this->expectExceptionObject(CaptureException::invalidDatetime());

        new CaptureResponse([
            'version' => '1.0',
            'reference' => 'ABCD123',
            'cdr' => '1',
            'lib' => 'paiement accepte',
            'aut' => '123456',
            'date_debit' => 'juin 2019'
        ]);

    }

    public function testCaptureResponseExceptionInvalidInvoiceType()
    {
        $this->expectExceptionObject(CaptureException::invalidInvoiceType('invalid'));

        new CaptureResponse([
            'version' => '1.0',
            'reference' => 'ABCD123',
            'cdr' => '1',
            'type_facture' => 'invalid',
            'lib' => 'paiement accepte',
            'aut' => '123456',
        ]);

    }


}