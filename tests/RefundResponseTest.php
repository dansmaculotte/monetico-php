<?php

use DansMaCulotte\Monetico\Refund\RefundResponse;
use PHPUnit\Framework\TestCase;
use DansMaCulotte\Monetico\Exceptions\Exception;

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

        $this->assertTrue($response instanceof RefundResponse);
    }

    public function testRefundResponseConstructExceptionInvalidReference()
    {
        $this->expectExceptionObject(Exception::invalidReference('thisisatoolongreference'));

        new RefundResponse([
            'version' => '1.0',
            'reference' => 'thisisatoolongreference',
            'cdr' => '1',
            'lib' => 'paiement accepte',
            'numero_dossier' => '123',
            'type_facture' => 'complementaire'
        ]);
    }

    public function testRefundResponseConstructExceptionInvalidFileNumber()
    {
        $this->expectExceptionObject(Exception::invalidReference('thisisatoolongreference'));

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
        $this->expectExceptionObject(Exception::invalidInvoiceType('invalid'));

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