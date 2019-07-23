<?php

use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Exceptions\RecoveryException;
use DansMaCulotte\Monetico\Recovery\Response;
use PHPUnit\Framework\TestCase;

class RecoveryResponseTest extends TestCase
{
    public function testRecoveryResponseConstruct()
    {
        $response = new Response([
            'version' => '1.0',
            'reference' => '000000000145',
            'cdr' => '1',
            'lib' => 'paiement accepte',
            'aut' => '123456',
        ]);

        $this->assertTrue($response instanceof Response);
    }

    public function testRecoveryResponseWithAuthorization()
    {
        $response = new Response([
            'version' => '1.0',
            'reference' => '000000000145',
            'cdr' => '1',
            'lib' => 'paiement accepte',
            'aut' => '123456',
            'phonie' => 'oui',
            'montant_estime' => '10EUR ',
            'date_autorisation' => '2019-05-20',
            'montant_debite' => '5EUR',
            'date_debit' => '2019-05-30',
            'numero_dossier' => 'doss123456',
            'type_facture' => 'preauto',
        ]);

        $this->assertTrue($response instanceof Response);
    }

    public function testRecoveryResponseExceptionInvalidFileNumber()
    {
        $this->expectExceptionObject(Exception::invalidResponseFileNumber('thisisawrongreference'));

        new Response([
            'version' => '1.0',
            'reference' => 'ABCD123',
            'cdr' => '1',
            'lib' => 'paiement accepte',
            'aut' => '123456',
            'numero_dossier' => 'thisisawrongreference'
        ]);
    }

    public function testRecoveryResponseExceptionInvalidAuthDatetime()
    {
        $this->expectExceptionObject(RecoveryException::invalidResponseAuthorizationDate());

        new Response([
            'version' => '1.0',
            'reference' => 'ABCD123',
            'cdr' => '1',
            'lib' => 'paiement accepte',
            'aut' => '123456',
            'date_autorisation' => 'juin 2019'
        ]);
    }

    public function testRecoveryResponseExceptionInvalidDebitDatetime()
    {
        $this->expectExceptionObject(RecoveryException::invalidResponseDebitDate());

        new Response([
            'version' => '1.0',
            'reference' => 'ABCD123',
            'cdr' => '1',
            'lib' => 'paiement accepte',
            'aut' => '123456',
            'date_debit' => 'juin 2019'
        ]);
    }

    public function testRecoveryResponseExceptionInvalidInvoiceType()
    {
        $this->expectExceptionObject(Exception::invalidResponseInvoiceType('invalid'));

        new Response([
            'version' => '1.0',
            'reference' => 'ABCD123',
            'cdr' => '1',
            'type_facture' => 'invalid',
            'lib' => 'paiement accepte',
            'aut' => '123456',
        ]);
    }
}
