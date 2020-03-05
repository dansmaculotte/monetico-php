<?php


use DansMaCulotte\Monetico\Exceptions\Exception;
use DansMaCulotte\Monetico\Resources\CartResource;
use PHPUnit\Framework\TestCase;

class ResourceTest extends TestCase
{
    public function testThrowExceptionInvalidParameterOnConstruct()
    {
        $this->expectExceptionObject(Exception::invalidResourceParameter('test'));
        $cart = new CartResource([
            'test' => 'error'
        ]);
    }

    public function testThrowExceptionInvalidParameterWithSet()
    {
        $this->expectExceptionObject(Exception::invalidResourceParameter('test'));
        $cart = new CartResource();
        $cart->setParameter('test', 'error');
    }

    public function testThrowExceptionInvalidParameterWithGet()
    {
        $this->expectExceptionObject(Exception::invalidResourceParameter('test'));
        $cart = new CartResource();
        $cart->getParameter('test');
    }

    public function testReturnNullWithGet()
    {
        $cart = new CartResource();
        $this->assertNull($cart->getParameter('giftCardAmount'));
    }
}
