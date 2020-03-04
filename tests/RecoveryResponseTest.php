<?php

use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Exceptions\RecoveryException;
use DansMaCulotte\Monetico\Responses\RecoveryResponse;
use PHPUnit\Framework\TestCase;

class RecoveryResponseTest extends TestCase
{
    public function testRecoveryResponseConstruct()
    {
        $response = new RecoveryResponse([
            'version' => '1.0',
            'reference' => '000000000145',
            'cdr' => '1',
            'lib' => 'paiement accepte',
            'aut' => '123456',
        ]);

        $this->assertInstanceOf(RecoveryResponse::class, $response);
    }

    public function testRecoveryResponseWithAuthorization()
    {
        $response = new RecoveryResponse([
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

        $this->assertInstanceOf(RecoveryResponse::class, $response);
    }

    public function testRecoveryResponseConstructExceptionMissingResponseKey()
    {
        $this->expectExceptionObject(Exception::missingResponseKey('cdr'));
        new RecoveryResponse([
            'version' => '1.0',
            'reference' => '000000000145',
            'lib' => 'paiement accepte',
            'aut' => '123456',
        ]);
    }

    public function testRecoveryResponseExceptionInvalidFileNumber()
    {
        $this->expectExceptionObject(Exception::invalidResponseFileNumber('thisisawrongreference'));

        new RecoveryResponse([
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

        new RecoveryResponse([
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

        new RecoveryResponse([
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

        new RecoveryResponse([
            'version' => '1.0',
            'reference' => 'ABCD123',
            'cdr' => '1',
            'type_facture' => 'invalid',
            'lib' => 'paiement accepte',
            'aut' => '123456',
        ]);
    }
}
