<?php

use Carbon\Carbon;
use DansMaCulotte\Monetico\Recovery\Cancel;
use PHPUnit\Framework\TestCase;

class CancelTest extends TestCase
{
    public function testCancelConstruct()
    {
        $cancel = new Cancel([
            'datetime' => Carbon::create(2019, 2, 1),
            'orderDatetime' => Carbon::create(2019, 1, 1),
            'reference' => 'ABC123',
            'language' => 'FR',
            'currency' => 'EUR',
            'amount' => 100,
            'amountRecovered' => 0,
        ]);

        $this->assertTrue($cancel instanceof Cancel);
    }
}