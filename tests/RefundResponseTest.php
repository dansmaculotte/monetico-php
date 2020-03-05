<?php

use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Responses\RefundResponse;
use PHPUnit\Framework\TestCase;

class RefundResponseTest extends TestCase
{
    public function testRefundResponseConstruct()
    {
        $response = new RefundResponse([
            'version' => '1.0',
            'reference' => '000000000145',
            'cdr' => '1',
            'lib' => 'paiement accepte',
            'numero_dossier' => '123',
            'type_facture' => 'complementaire'
        ]);

        $this->assertInstanceOf(RefundResponse::class, $response);
    }

    public function testRefundResponseConstructExceptionMissingResponseKey()
    {
        $this->expectExceptionObject(Exception::missingResponseKey('cdr'));

        new RefundResponse([
            'version' => '1.0',
            'reference' => '000000000145',
            'lib' => 'paiement accepte',
            'numero_dossier' => '123',
            'type_facture' => 'complementaire'
        ]);
    }


    public function testRefundResponseConstructExceptionInvalidFileNumber()
    {
        $this->expectExceptionObject(Exception::invalidResponseFileNumber('thisisatoolongreference'));

        new RefundResponse([
            'version' => '1.0',
            'reference' => 'ABC',
            'cdr' => '1',
            'lib' => 'paiement accepte',
            'numero_dossier' => 'thisisatoolongreference',
            'type_facture' => 'complementaire'
        ]);
    }

    public function testRefundResponseConstructExceptionInvalidInvoiceType()
    {
        $this->expectExceptionObject(Exception::invalidResponseInvoiceType('invalid'));

        new RefundResponse([
            'version' => '1.0',
            'reference' => 'ABC',
            'cdr' => '1',
            'lib' => 'paiement accepte',
            'numero_dossier' => 'ABC',
            'type_facture' => 'invalid'
        ]);
    }
}
