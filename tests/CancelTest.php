<?php

use Carbon\Carbon;
use DansMaCulotte\Monetico\Recovery\Cancel;
use PHPUnit\Framework\TestCase;
use DansMaCulotte\Monetico\Exceptions\Exception;

class CancelTest extends TestCase
{
    public function testCancelConstruct()
    {
        $cancel = new Cancel([
            'dateTime' => Carbon::create(2019, 2, 1),
            'orderDate' => Carbon::create(2019, 1, 1),
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountRecovered' => 0,
        ]);

        $this->assertTrue($cancel instanceof Cancel);
    }

    public function testRecoveryConstructExceptionInvalidDatetime()
    {
        $this->expectExceptionObject(Exception::invalidDatetime());

        new Cancel([
            'dateTime' => 'invalid',
            'orderDate' => Carbon::create(2019, 1, 1),
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountRecovered' => 50,
        ]);
    }

    public function testRecoveryConstructExceptionInvalidOrderDatetime()
    {
        $this->expectExceptionObject(Exception::invalidOrderDate());

        new Cancel([
            'dateTime' => Carbon::create(2019, 1, 1),
            'orderDate' => 'invalid',
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountRecovered' => 50,
        ]);
    }

    public function testRecoveryConstructExceptionInvalidReference()
    {
        $this->expectExceptionObject(Exception::invalidReference('thisisatoolongreference'));

        new Cancel([
            'dateTime' => Carbon::create(2019, 2, 1),
            'orderDate' => Carbon::create(2019, 1, 1),
            'reference' => 'thisisatoolongreference',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountRecovered' => 50,
        ]);
    }

    public function testRecoveryConstructExceptionInvalidLanguage()
    {
        $this->expectExceptionObject(Exception::invalidLanguage('English'));

        new Cancel([
            'dateTime' => Carbon::create(2019, 2, 1),
            'orderDate' => Carbon::create(2019, 1, 1),
            'reference' => 'ABC123',
            'language' => 'English',
            'currency' => 'EUR',
            'amount' => 100,
            'amountRecovered' => 50,
        ]);
    }
}