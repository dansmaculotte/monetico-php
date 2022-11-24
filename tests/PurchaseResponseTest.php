<?php

use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Exceptions\PurchaseException;
use DansMaCulotte\Monetico\Monetico;
use DansMaCulotte\Monetico\Responses\PurchaseResponse;
use PHPUnit\Framework\TestCase;

require_once 'Credentials.fake.php';

class PurchaseResponseTest extends TestCase
{
    private function generateSeal($data)
    {
        ksort($data);
        $query = http_build_query($data, null, '*');
        $query = urldecode($query);

        $hash =  strtoupper(hash_hmac(
            'sha1',
            $query,
            Monetico::getUsableKey(SECURITY_KEY)
        ));

        return $hash;
    }

    private $data = [
        'authentification' => 'ewogICAiZGV0YWlscyIgOiB7CiAgICAgICJQQVJlcyIgOiAiWSIsCiAgICAgICJWRVJlcyIgOiAiWSIsCiAgICAgICJzdGF0dXMzRFMiIDogMQogICB9LAogICAicHJvdG9jb2wiIDogIjNEU2VjdXJlIiwKICAgInN0YXR1cyIgOiAiYXV0aGVudGljYXRlZCIsCiAgICJ2ZXJzaW9uIiA6ICIxLjAuMiIKfQo=',
        'bincb' => '000003',
        'brand' => 'MC',
        'code-retour' => 'payetest',
        'cvx' => 'oui',
        'date' => '11/07/2019_a_10:51:19',
        'hpancb' => '07CDB0331260C06818027855F795C9F726585286',
        'ipclient' => '80.15.24.220',
        'MAC' => '', // needs to be generated
        'modepaiement' => 'CB',
        'montant' => '42.42EUR',
        'numauto' => '000000',
        'originecb' => 'FRA',
        'originetr' => 'FRA',
        'reference' => 'D2345677',
        'texte-libre' => 'PHPUnit',
        'TPE' => '9344512',
        'vld' => '1219',
        'usage' => 'credit',
        'typecompte' => 'particulier',
        'ecard' => 'non',
    ];

    /** @test */
    public function should_construct_PaymentResponse_from_test_data()
    {
        $response = new PurchaseResponse($this->data);
        $this->assertInstanceOf(PurchaseResponse::class, $response);
    }

    /** @test */
    public function should_throw_an_exception_when_a_key_is_missing()
    {
        $this->expectExceptionObject(Exception::missingResponseKey('TPE'));

        new PurchaseResponse([]);
    }

    /** @test */
    public function should_throw_an_exception_when_date_format_is_incorrect()
    {
        $this->expectExceptionObject(Exception::invalidResponseDateTime());

        $data = $this->data;
        $data['date'] = 'oups';

        new PurchaseResponse($data);
    }


    /** @test */
    public function should_throw_an_exception_when_code_retour_is_unknown()
    {
        $this->expectExceptionObject(PurchaseException::invalidResponseReturnCode('foo'));

        $data = $this->data;
        $data['code-retour'] = 'foo';

        new PurchaseResponse($data);
    }

    /** @test */
    public function should_throw_an_exception_when_the_card_verification_status_is_unknown()
    {
        $this->expectExceptionObject(PurchaseException::invalidResponseCardVerificationStatus('nope'));

        $data = $this->data;
        $data['cvx'] = 'nope';

        new PurchaseResponse($data);
    }

    public function should_throw_an_exception_when_the_card_brand_is_unknown()
    {
        $this->expectExceptionObject(PurchaseException::invalidResponseCardBrand('foo'));

        $data = $this->data;
        $data['brand'] = 'foo';

        new PurchaseResponse($data);
    }

    /** @test */
    public function should_throw_an_exception_when_motif_refus_is_unknown()
    {
        $this->expectExceptionObject(PurchaseException::invalidResponseRejectReason('foobar'));

        $data = $this->data;
        $data['motifrefus'] = 'foobar';

        new PurchaseResponse($data);
    }

    /** @test */
    public function should_throw_an_exception_when_mode_paiement_is_unknown()
    {
        $this->expectExceptionObject(PurchaseException::invalidResponsePaymentMethod('bar'));

        $data = $this->data;
        $data['modepaiement'] = 'bar';

        new PurchaseResponse($data);
    }

    /** @test */
    public function should_thrown_an_exception_when_filtrage_cause_is_unknown()
    {
        $this->expectExceptionObject(PurchaseException::invalidResponseFilteredReason('10'));

        $data = $this->data;
        $data['filtragecause'] = '10';

        new PurchaseResponse($data);
    }

    /** @test */
    public function should_handle_payments_with_optional_data()
    {
        $data = $this->data;

        $data['montantech'] = '50EUR';
        $data['filtragevaleur'] = 'foobar';
        $data['filtrage_etat'] = 'test';
        $data['cbenregistree'] = '1';
        $data['cbmasquee'] = '1234XXXXXXXXXXX1234';
        $data['motifrefus'] = 'Interdit';
        $data['filtragecause'] = '1';
        $data['cbenregistree'] = '1';


        $response = new PurchaseResponse($data);

        $this->assertTrue($response->commitmentAmount === '50EUR');
        $this->assertTrue($response->filteredValue === 'foobar');
        $this->assertTrue($response->filteredStatus === 'test');
        $this->assertTrue($response->cardSaved === true);
        $this->assertTrue($response->cardMask === '1234XXXXXXXXXXX1234');
    }


    /** @test */
    public function should_decode_authentification_string()
    {
        $data = $this->data;

        $response = new PurchaseResponse($data);

        $this->assertEquals('3DSecure', $response->authentication->protocol);
        $this->assertEquals('authenticated', $response->authentication->status);
        $this->assertEquals('1.0.2', $response->authentication->version);
        $this->assertEmpty($response->authentication->details);
    }

    /** @test */
    public function should_validate_seal_string()
    {
        $data = [
            'authentification' => 'ewogICAiZGV0YWlscyIgOiB7CiAgICAgICJBUmVzIiA6ICJZIiwKICAgICAgImF1dGhlbnRpY2F0aW9uVmFsdWUiIDogIlFVRkNRa05EUkVSRlJVWkdRVUZDUWtORFJFUT0iLAogICAgICAibGlhYmlsaXR5U2hpZnQiIDogIlkiLAogICAgICAibWVyY2hhbnRQcmVmZXJlbmNlIiA6ICJub19wcmVmZXJlbmNlIiwKICAgICAgInRyYW5zYWN0aW9uSUQiIDogIjdjOTgyNTVhLWE5YzctNDYxYy1hZDEyLWM3NjM5MzczZDljYiIKICAgfSwKICAgInByb3RvY29sIiA6ICIzRFNlY3VyZSIsCiAgICJzdGF0dXMiIDogImF1dGhlbnRpY2F0ZWQiLAogICAidmVyc2lvbiIgOiAiMi4xLjAiCn0K',
            'bincb' => '000003',
            'brand' => 'MC',
            'code-retour' => 'payetest',
            'cvx' => 'oui',
            'hpancb' => '6FF1313F3B6FE9B053B21CBDEE516603CB8CF01E',
            'ipclient' => '80.15.24.220',
            'modepaiement' => 'CB',
            'numauto' => '000000',
            'originecb' => 'FRA',
            'originetr' => 'FRA',
            'vld' => '1221',
            'date' => '23/07/2019_a_11:55:47',
            'montant' => '42.42EUR',
            'reference' => '12345678',
            'texte-libre' => 'PHPUnit',
            'TPE' => '9344512',
            'montantech' => '50EUR',
            'filtragevaleur' => 'foobar',
            'filtrage_etat' => 'test',
            'cbenregistree' => '1',
            'cbmasquee' => '1234XXXXXXXXXXX1234',
            'motifrefus' => 'Interdit',
            'filtragecause' => '1',
            'usage' => 'credit',
            'typecompte' => 'particulier',
            'ecard' => 'non',
            'MAC' => '2D77EAC00BA7322FFDC3E3261E49614B252F3097'
        ];

        $response = new PurchaseResponse($data);
        $sealValid = $response->validateSeal(EPT_CODE, Monetico::getUsableKey(SECURITY_KEY));
        $this->assertTrue($sealValid);
    }


    /** @test */
    public function should_validate_seal_on_canceled_payment()
    {
        $data = [
            'TPE' => '9344512',
            'date' => '10/06/2022_a_23:22:55',
            'montant' => '191.52EUR',
            'reference' => '4KKHUGNUNOGG',
            'texte-libre' => 'PHP Unit',
            'code-retour' => 'Annulation',
            'cvx' => 'oui',
            'vld' => '0924',
            'brand' => 'CB',
            'motifrefus' => '3DSecure',
            'motifrefusautorisation' => '-',
            'usage' => 'debit',
            'typecompte' => 'particulier',
            'ecard' => 'non',
            'originecb' => 'FRA',
            'bincb' => '000003',
            'hpancb' => '6FF1313F3B6FE9B053B21CBDEE516603CB8CF01E',
            'ipclient' => '127.0.0.1',
            'originetr' => 'FRA',
            'modepaiement' => 'CB',
            'authentification' => 'ewogICAiZGV0YWlscyIgOiB7CiAgICAgICJBUmVzIiA6ICJZIiwKICAgICAgImF1dGhlbnRpY2F0aW9uVmFsdWUiIDogIlFVRkNRa05EUkVSRlJVWkdRVUZDUWtORFJFUT0iLAogICAgICAibGlhYmlsaXR5U2hpZnQiIDogIlkiLAogICAgICAibWVyY2hhbnRQcmVmZXJlbmNlIiA6ICJub19wcmVmZXJlbmNlIiwKICAgICAgInRyYW5zYWN0aW9uSUQiIDogIjdjOTgyNTVhLWE5YzctNDYxYy1hZDEyLWM3NjM5MzczZDljYiIKICAgfSwKICAgInByb3RvY29sIiA6ICIzRFNlY3VyZSIsCiAgICJzdGF0dXMiIDogImF1dGhlbnRpY2F0ZWQiLAogICAidmVyc2lvbiIgOiAiMi4xLjAiCn0K',
            'MAC' => 'D258E699FE4C52199557336C89B02C81D09244F6',
        ];

        $response = new PurchaseResponse($data);
        $sealValid = $response->validateSeal(EPT_CODE, Monetico::getUsableKey(SECURITY_KEY));
        $this->assertTrue($sealValid);
    }

    /** @test */
    public function should_validate_payment_even_if_authentification_string_is_empty()
    {
        $data = [
            'TPE' => EPT_CODE,
            'date' => '18/11/2022_a_12:10:48',
            'montant' => '11.90EUR',
            'reference' => 'JXNWHZLUY65F',
            'texte-libre' => 'PHP Unit',
            'code-retour' => 'paiement',
            'cvx' => 'oui',
            'vld' => '1125',
            'brand' => 'MC',
            'numauto' => '248650',
            'usage' => 'debit',
            'typecompte' => 'particulier',
            'ecard' => 'non',
            'originecb' => 'ESP',
            'bincb' => '51638300',
            'hpancb' => '6FF1313F3B6FE9B053B21CBDEE516603CB8CF01E',
            'ipclient' => '127.0.0.1',
            'originetr' => 'FRA',
            'cbmasquee' => '51638300******66',
            'modepaiement' => 'CB',
            'authentification' => 'bnVsbAo=',
            'MAC' => '31F710AC5A6A73CC13FF20FD936AA59C099B155E',
        ];
        $response = new PurchaseResponse($data);
        $sealValid = $response->validateSeal(EPT_CODE, Monetico::getUsableKey(SECURITY_KEY));
        $this->assertTrue($sealValid);
    }

    /** @test */
    public function should_xxx()
    {
        $data = [
            'TPE' => EPT_CODE,
            'date' => '15/11/2022_a_17:42:23',
            'montant' => '17.75EUR',
            'reference' => 'KFPHFV7NCXWB',
            'texte-libre' => 'php unit',
            'code-retour' => 'Annulation',
            'cvx' => 'oui',
            'vld' => '0125',
            'brand' => 'VI',
            'motifrefus' => '-',
            'motifrefusautorisation' => '-',
            'usage' => 'debit',
            'typecompte' => 'particulier',
            'ecard' => 'non',
            'originecb' => 'FRA',
            'bincb' => '46334308',
            'hpancb' => '07CDB0331260C06818027855F795C9F726585286',
            'ipclient' => '127.0.0.1',
            'originetr' => 'FRA',
            'cbmasquee' => '1234XXXXXXXXXXX1234',
            'modepaiement' => 'CB',
            'authentification' => 'ewogICAiZGV0YWlscyIgOiB7CiAgICAgICJBUmVzIiA6ICJDIiwKICAgICAgIkNSZXMiIDogIk4iLAogICAgICAibGlhYmlsaXR5U2hpZnQiIDogIk5BIiwKICAgICAgIm1lcmNoYW50UHJlZmVyZW5jZSIgOiAibm9fcHJlZmVyZW5jZSIsCiAgICAgICJ0cmFuc2FjdGlvbklEIiA6ICIwMGRmMzY0Ni0yMzE4LTRjOTItYjgzMC03MThlZjQ4Y2NjMTUiCiAgIH0sCiAgICJwcm90b2NvbCIgOiAiM0RTZWN1cmUiLAogICAic3RhdHVzIiA6ICJhdXRoZW50aWNhdGlvbl9ub3RfcGVyZm9ybWVkIiwKICAgInZlcnNpb24iIDogIjIuMS4wIgp9Cg==',
            'MAC' => 'C3526D4B3B8AEFDC73483B559FF641C592203643',
        ];
        $response = new PurchaseResponse($data);
        $sealValid = $response->validateSeal(EPT_CODE, Monetico::getUsableKey(SECURITY_KEY));
        $this->assertTrue($sealValid);
    }
}
