<?php

use DansMaCulotte\Monetico\Responses\CancelResponse;
use PHPUnit\Framework\TestCase;

class CancelResponseTest extends TestCase
{
    public function testCancelResponseConstruct()
    {
        $response = new CancelResponse([
            'version' => '1.0',
            'reference' => '000000000145',
            'cdr' => '1',
            'lib' => 'commande annulee',
            'aut' => '123456',
            'montant_estime' => '1.01EUR',
            'date_autorisation' => '2019-05-21',
            'numero_dossier' => '1011',
            'type_facture' => 'preauto',
        ]);

        $this->assertInstanceOf(CancelResponse::class, $response);
    }
}
