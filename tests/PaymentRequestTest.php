<?php

use Carbon\Carbon;
use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Exceptions\PaymentException;
use DansMaCulotte\Monetico\Monetico;
use DansMaCulotte\Monetico\Requests\PaymentRequest;
use DansMaCulotte\Monetico\Resources\AddressResource;
use DansMaCulotte\Monetico\Resources\ClientResource;
use PHPUnit\Framework\TestCase;

require_once 'Credentials.fake.php';

class PaymentRequestTest extends TestCase
{
    public function testPaymentConstruct()
    {
        $payment = new PaymentRequest([
            'reference' => 'ABCDEF123',
            'description' => 'PHPUnit',
            'language' => 'FR',
            'email' => 'john@english.fr',
            'amount' => 42.42,
            'currency' => 'EUR',
            'dateTime' => Carbon::create(2019, 1, 1),
            'successUrl' => 'https://127.0.0.1/success',
            'errorUrl' => 'https://127.0.0.1/error'
        ]);

        $this->assertTrue($payment instanceof PaymentRequest);
    }

    public function testPaymentUrl()
    {
        $payment = new PaymentRequest([
            'reference' => 'ABCDEF123',
            'description' => 'PHPUnit',
            'language' => 'FR',
            'email' => 'john@english.fr',
            'amount' => 42.42,
            'currency' => 'EUR',
            'dateTime' => Carbon::create(2019, 1, 1),
            'successUrl' => 'https://127.0.0.1/success',
            'errorUrl' => 'https://127.0.0.1/error'
        ]);

        $url = $payment->getUrl();

        $this->assertTrue($url === 'https://p.monetico-services.com/paiement.cgi');

        $url = $payment->getUrl(true);

        $this->assertTrue($url === 'https://p.monetico-services.com/test/paiement.cgi');
    }

    public function testPaymentExceptionReference()
    {
        $this->expectExceptionObject(Exception::invalidReference('thisisabigerroryouknow'));

        new PaymentRequest([
            'reference' => 'thisisabigerroryouknow',
            'description' => 'PHPUnit',
            'language' => 'FR',
            'email' => 'john@english.fr',
            'amount' => 42.42,
            'currency' => 'EUR',
            'dateTime' => Carbon::create(2019, 1, 1),
            'successUrl' => 'https://127.0.0.1/success',
            'errorUrl' => 'https://127.0.0.1/error'
        ]);
    }

    public function testPaymentExceptionLanguage()
    {
        $this->expectExceptionObject(Exception::invalidLanguage('WTF'));

        new PaymentRequest([
            'reference' => 'ABCDEF123',
            'description' => 'PHPUnit',
            'language' => 'WTF',
            'email' => 'john@english.fr',
            'amount' => 42.42,
            'currency' => 'EUR',
            'dateTime' => Carbon::create(2019, 1, 1),
            'successUrl' => 'https://127.0.0.1/success',
            'errorUrl' => 'https://127.0.0.1/error'
        ]);
    }

    public function testPaymentExceptionDatetime()
    {
        $this->expectExceptionObject(Exception::invalidDatetime());

        new PaymentRequest([
            'reference' => 'ABCDEF123',
            'description' => 'PHPUnit',
            'language' => 'FR',
            'email' => 'john@english.fr',
            'amount' => 42.42,
            'currency' => 'EUR',
            'dateTime' => '42',
            'successUrl' => 'https://127.0.0.1/success',
            'errorUrl' => 'https://127.0.0.1/error'
        ]);
    }

    public function testPaymentOptions()
    {
        $payment = new PaymentRequest([
            'reference' => 'ABCDEF123',
            'description' => 'PHPUnit',
            'language' => 'FR',
            'email' => 'john@english.fr',
            'amount' => 42.42,
            'currency' => 'EUR',
            'dateTime' => Carbon::create(2019, 1, 1),
            'successUrl' => 'https://127.0.0.1/success',
            'errorUrl' => 'https://127.0.0.1/error'
        ]);

        $payment->setCardAlias('foobar');
        $this->assertArrayHasKey('aliascb', $payment->options);
        $this->assertTrue($payment->options['aliascb'] === 'foobar');

        $payment->setForceCard();
        $this->assertArrayHasKey('forcesaisiecb', $payment->options);
        $this->assertTrue($payment->options['forcesaisiecb'] === '1');

        $payment->setForceCard(false);
        $this->assertTrue($payment->options['forcesaisiecb'] === '0');

        $payment->setDisable3DS();
        $this->assertArrayHasKey('3dsdebrayable', $payment->options);
        $this->assertTrue($payment->options['3dsdebrayable'] === '1');

        $payment->setDisable3DS(false);
        $this->assertTrue($payment->options['3dsdebrayable'] === '0');

        $payment->setSignLabel('FooBar');
        $this->assertArrayHasKey('libelleMonetique', $payment->options);
        $this->assertTrue($payment->options['libelleMonetique'] === 'FooBar');

        $payment->setDisabledPaymentWays([
            '1euro',
            '3xcb',
            '4xcb',
            'fivory',
            'paypal'
        ]);
        $this->assertArrayHasKey('desactivemoyenpaiement', $payment->options);
        $this->assertTrue($payment->options['desactivemoyenpaiement'] === '1euro,3xcb,4xcb,fivory,paypal');

        $payment->setDisabledPaymentWays([
            '1euro',
            '3xcb',
            '4xcb',
            'fivory',
            'foobar'
        ]);
        $this->assertArrayHasKey('desactivemoyenpaiement', $payment->options);
        $this->assertTrue($payment->options['desactivemoyenpaiement'] === '1euro,3xcb,4xcb,fivory');
    }

    public function testPaymentCommitments()
    {
        $payment = new PaymentRequest(
            [
                'reference' => 'ABCDEF123',
                'description' => 'PHPUnit',
                'language' => 'FR',
                'email' => 'john@english.fr',
                'amount' => 200,
                'currency' => 'EUR',
                'dateTime' => Carbon::create(2019, 1, 1),
                'successUrl' => 'https://127.0.0.1/success',
                'errorUrl' => 'https://127.0.0.1/error'
            ],
            [
                [
                    'date' => '06/01/2019',
                    'amount' => 50,
                ],
                [
                    'date' => '12/01/2019',
                    'amount' => 100,
                ],
                [
                    'date' => '24/01/2019',
                    'amount' => 20,
                ],
                [
                    'date' => '02/02/2019',
                    'amount' => 30,
                ],
            ]
        );

        $seal = $payment->generateSeal(
            'FOO',
            []
        );

        $fields = $payment->generateFields(
            'FOO',
            $payment->fieldsToArray(
                'FOOBAR',
                3.0,
                'FOO'
            )
        );

        $this->assertIsArray($fields);
        $this->assertArrayHasKey('nbrech', $fields);
        $this->assertTrue($fields['nbrech'] === 4);

        $this->assertArrayHasKey('dateech1', $fields);
        $this->assertTrue($fields['dateech1'] === '06/01/2019');

        $this->assertArrayHasKey('montantech1', $fields);
        $this->assertTrue($fields['montantech1'] === '50EUR');

        $this->assertArrayHasKey('dateech2', $fields);
        $this->assertTrue($fields['dateech2'] === '12/01/2019');

        $this->assertArrayHasKey('montantech2', $fields);
        $this->assertTrue($fields['montantech2'] === '100EUR');

        $this->assertArrayHasKey('dateech3', $fields);
        $this->assertTrue($fields['dateech3'] === '24/01/2019');

        $this->assertArrayHasKey('montantech3', $fields);
        $this->assertTrue($fields['montantech3'] === '20EUR');

        $this->assertArrayHasKey('dateech4', $fields);
        $this->assertTrue($fields['dateech4'] === '02/02/2019');

        $this->assertArrayHasKey('montantech4', $fields);
        $this->assertTrue($fields['montantech4'] === '30EUR');
    }

    public function testSetOrderContext()
    {
        $payment = new PaymentRequest([
            'reference' => 'ABCDEF123',
            'description' => 'PHPUnit',
            'language' => 'FR',
            'email' => 'john@english.fr',
            'amount' => 42.42,
            'currency' => 'EUR',
            'dateTime' => Carbon::create(2019, 1, 1),
            'successUrl' => 'https://127.0.0.1/success',
            'errorUrl' => 'https://127.0.0.1/error'
        ]);

        $addressBilling = new AddressResource('7 rue melingue', 'Caen', '14000', 'France');
        $payment->setAddressBilling($addressBilling);

        $addressShipping = new AddressResource('7 rue melingue', 'Caen', '14000', 'France');
        $addressShipping->setOptionalField('email', 'john@english.fr');
        $payment->setAddressShipping($addressShipping);

        $client = new ClientResource('MR', 'FooBoo', 'Foo', 'Boo');
        $payment->setClient($client);

        $this->assertEquals('7 rue melingue', $payment->addressShipping->data['addressLine1']);
        $this->assertEquals('Caen', $payment->addressShipping->data['city']);
        $this->assertEquals('14000', $payment->addressShipping->data['postalCode']);
        $this->assertEquals('France', $payment->addressShipping->data['country']);
        $this->assertEquals('john@english.fr', $payment->addressShipping->data['email']);

        $this->assertEquals('7 rue melingue', $payment->addressBilling->data['addressLine1']);
        $this->assertEquals('Caen', $payment->addressBilling->data['city']);
        $this->assertEquals('14000', $payment->addressBilling->data['postalCode']);
        $this->assertEquals('France', $payment->addressBilling->data['country']);

        $this->assertEquals('MR', $payment->client->data['civility']);
        $this->assertEquals('FooBoo', $payment->client->data['name']);
        $this->assertEquals('Foo', $payment->client->data['firstName']);
        $this->assertEquals('Boo', $payment->client->data['lastName']);
    }

    public function testSet3DSecure()
    {
        $payment = new PaymentRequest([
            'reference' => '12345679',
            'description' => 'PHPUnit',
            'language' => 'FR',
            'email' => 'john@english.fr',
            'amount' => 42.42,
            'currency' => 'EUR',
            'dateTime' => Carbon::create(2019, 07, 23),
            'successUrl' => 'https://127.0.0.1/success',
            'errorUrl' => 'https://127.0.0.1/error'
        ]);

        $payment->setThreeDSecureChallenge('challenge_mandated');
        $payment->setCardAlias('martin');
        $payment->setSignLabel('toto');

        $fields = $payment->fieldsToArray(
            EPT_CODE,
            '3.0',
            COMPANY_CODE
        );

        $seal = $payment->generateSeal(
            Monetico::getUsableKey(SECURITY_KEY),
            $fields
        );

        $fields = $payment->generateFields(
            $seal,
            $fields
        );

        $this->assertEquals($fields['ThreeDSecureChallenge'], 'challenge_mandated');
    }

    public function testPaymentException3DSecure()
    {
        $this->expectExceptionObject(PaymentException::invalidThreeDSecureChallenge('invalid_choice'));

        $payment = new PaymentRequest([
            'reference' => 'ABCDEF123',
            'description' => 'PHPUnit',
            'language' => 'FR',
            'email' => 'john@english.fr',
            'amount' => 42.42,
            'currency' => 'EUR',
            'dateTime' => Carbon::create(2019, 1, 1),
            'successUrl' => 'https://127.0.0.1/success',
            'errorUrl' => 'https://127.0.0.1/error'
        ]);

        $payment->setThreeDSecureChallenge('invalid_choice');
    }
}
