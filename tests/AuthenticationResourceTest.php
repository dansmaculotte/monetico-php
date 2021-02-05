<?php

use DansMaCulotte\Monetico\Exceptions\AuthenticationException;
use DansMaCulotte\Monetico\Resources\AuthenticationResource;
use PHPUnit\Framework\TestCase;

class AuthenticationResourceTest extends TestCase
{
    public function testAuthenticationConstruct()
    {
        $authentication = new AuthenticationResource(
            '3DSecure',
            'authenticated',
            '2.1.0',
            [
                'liabilityShift' => 'Y',
                'ARes' => 'C',
                'CRes' => 'Y',
                'merchantPreference' => 'no_preference',
                'transactionID' => '555bd9d9-1cf1-4ba8-b37c-1a96bc8b603a',
                'authenticationValue' => 'cmJvd0I4SHk3UTRkYkFSQ3FYY3U=',
                'disablingReason' => 'seuilnonatteint'
            ]
        );
        $this->assertInstanceOf(AuthenticationResource::class, $authentication);
    }

    public function testAuthenticationConstructExceptionInvalidProtocol()
    {
        $this->expectExceptionObject(AuthenticationException::invalidProtocol('invalid'));

        new AuthenticationResource(
            'invalid',
            'authenticated',
            '2.1.0',
            [
                'liabilityShift' => 'Y',
                'ARes' => 'C',
                'CRes' => 'Y',
                'merchantPreference' => 'no_preference',
                'transactionID' => '555bd9d9-1cf1-4ba8-b37c-1a96bc8b603a',
                'authenticationValue' => 'cmJvd0I4SHk3UTRkYkFSQ3FYY3U='
            ]
        );
    }

    public function testAuthenticationConstructExceptionInvalidStatus()
    {
        $this->expectExceptionObject(AuthenticationException::invalidStatus('invalid'));

        new AuthenticationResource(
            '3DSecure',
            'invalid',
            '2.1.0',
            [
                "liabilityShift" => "Y",
                "ARes" => "C",
                "CRes" => "Y",
                "merchantPreference" => "no_preference",
                "transactionID" => "555bd9d9-1cf1-4ba8-b37c-1a96bc8b603a",
                'authenticationValue' => "cmJvd0I4SHk3UTRkYkFSQ3FYY3U="
            ]
        );
    }

    public function testAuthenticationConstructExceptionInvalidVersion()
    {
        $this->expectExceptionObject(AuthenticationException::invalidVersion('0.5'));

        new AuthenticationResource(
            '3DSecure',
            'authenticated',
            '0.5',
            [
                "liabilityShift" => "Y",
                "ARes" => "C",
                "CRes" => "Y",
                "merchantPreference" => "no_preference",
                "transactionID" => "555bd9d9-1cf1-4ba8-b37c-1a96bc8b603a",
                "authenticationValue" => "cmJvd0I4SHk3UTRkYkFSQ3FYY3U="
            ]
        );
    }

    public function testAuthenticationConstructExceptionInvalidLiabilityShift()
    {
        $this->expectExceptionObject(AuthenticationException::invalidLiabilityShift('X'));

        new AuthenticationResource(
            '3DSecure',
            'authenticated',
            '2.1.0',
            [
                "liabilityShift" => "X",
                "ARes" => "C",
                "CRes" => "Y",
                "merchantPreference" => "no_preference",
                "transactionID" => "555bd9d9-1cf1-4ba8-b37c-1a96bc8b603a",
                "authenticationValue" => "cmJvd0I4SHk3UTRkYkFSQ3FYY3U="
            ]
        );
    }

    public function testAuthenticationConstructExceptionInvalidVERes()
    {
        $this->expectExceptionObject(AuthenticationException::invalidVERes('X'));

        new AuthenticationResource(
            '3DSecure',
            'authenticated',
            '2.1.0',
            [
                "liabilityShift" => "Y",
                "ARes" => "C",
                "VERes" => "X",
                "CRes" => "Y",
                "merchantPreference" => "no_preference",
                "transactionID" => "555bd9d9-1cf1-4ba8-b37c-1a96bc8b603a",
                "authenticationValue" => "cmJvd0I4SHk3UTRkYkFSQ3FYY3U="
            ]
        );
    }

    public function testAuthenticationConstructExceptionInvalidPARes()
    {
        $this->expectExceptionObject(AuthenticationException::invalidPARes('X'));

        new AuthenticationResource(
            '3DSecure',
            'authenticated',
            '2.1.0',
            [
                "liabilityShift" => "Y",
                "ARes" => "C",
                "PARes" => "X",
                "CRes" => "Y",
                "merchantPreference" => "no_preference",
                "transactionID" => "555bd9d9-1cf1-4ba8-b37c-1a96bc8b603a",
                "authenticationValue" => "cmJvd0I4SHk3UTRkYkFSQ3FYY3U="
            ]
        );
    }

    public function testAuthenticationConstructExceptionInvalidARes()
    {
        $this->expectExceptionObject(AuthenticationException::invalidARes('X'));

        new AuthenticationResource(
            '3DSecure',
            'authenticated',
            '2.1.0',
            [
                "liabilityShift" => "Y",
                "ARes" => "X",
                "CRes" => "N",
                "merchantPreference" => "no_preference",
                "transactionID" => "555bd9d9-1cf1-4ba8-b37c-1a96bc8b603a",
                "authenticationValue" => "cmJvd0I4SHk3UTRkYkFSQ3FYY3U="
            ]
        );
    }

    public function testAuthenticationConstructExceptionInvalidCRes()
    {
        $this->expectExceptionObject(AuthenticationException::invalidCRes('D'));

        new AuthenticationResource(
            '3DSecure',
            'authenticated',
            '2.1.0',
            [
                "liabilityShift" => "Y",
                "CRes" => "D",
                "merchantPreference" => "no_preference",
                "transactionID" => "555bd9d9-1cf1-4ba8-b37c-1a96bc8b603a",
                "authenticationValue" => "cmJvd0I4SHk3UTRkYkFSQ3FYY3U="
            ]
        );
    }

    public function testAuthenticationConstructExceptionInvalidMerchantPreference()
    {
        $this->expectExceptionObject(AuthenticationException::invalidMerchantPreference('invalid'));

        new AuthenticationResource(
            '3DSecure',
            'authenticated',
            '2.1.0',
            [
                "liabilityShift" => "Y",
                "merchantPreference" => "invalid",
                "transactionID" => "555bd9d9-1cf1-4ba8-b37c-1a96bc8b603a",
                "authenticationValue" => "cmJvd0I4SHk3UTRkYkFSQ3FYY3U="
            ]
        );
    }

    public function testAuthenticationConstructExceptionInvalidDDDSStatus()
    {
        $this->expectExceptionObject(AuthenticationException::invalidDDDSStatus(10));

        new AuthenticationResource(
            '3DSecure',
            'authenticated',
            '2.1.0',
            [
                "liabilityShift" => "Y",
                "transactionID" => "555bd9d9-1cf1-4ba8-b37c-1a96bc8b603a",
                "authenticationValue" => "cmJvd0I4SHk3UTRkYkFSQ3FYY3U=",
                "status3DS" => 10
            ]
        );
    }

    public function testAuthenticationConstructExceptionInvalidDisablingReason()
    {
        $this->expectExceptionObject(AuthenticationException::invalidDisablingReason('invalid'));

        new AuthenticationResource(
            '3DSecure',
            'authenticated',
            '2.1.0',
            [
                "liabilityShift" => "Y",
                "transactionID" => "555bd9d9-1cf1-4ba8-b37c-1a96bc8b603a",
                "authenticationValue" => "cmJvd0I4SHk3UTRkYkFSQ3FYY3U=",
                "disablingReason" => 'invalid'
            ]
        );
    }

    public function testAutenticationConstructWithErrorStatus()
    {
        $authentication = new AuthenticationResource(
            '3DSecure',
            'error',
            '2.1.0',
            []
        );

        $this->assertInstanceOf(AuthenticationResource::class, $authentication);
    }
}
