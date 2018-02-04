<?php

 namespace Tests\RWC\Endicia;

use PHPUnit\Framework\TestCase;
use RWC\Endicia\AbstractResponse;
use RWC\Endicia\CertifiedIntermediary;
use RWC\Endicia\RecreditRequestResponse;

class RecreditRequestResponseTest extends TestCase
{
    public function testFromXmlAssignsCertifiedIntermediary()
    {
        $xml = $this->getXml(
            str_repeat('4', 4),     // RequesterId
            '0',                    // Status
            'Very bad things here', // ErrorMessage
            str_repeat('a', 50),    // RequestID
            '4324325',              // AccountID
            444444444444,           // SerialNumber
            0.00,                   // PostageBalance
            0.00,                   // AscendingBalance
            'A',                    // AccountStatus
            '222222222222'          // DeviceID
        );

        $response = RecreditRequestResponse::fromXml($xml);
        $this->assertTrue($response->isSuccessful());
    }

    public function testAccountIdAgreement()
    {
        $value = '4324325';
        $xml = $this->getXml(
            str_repeat('4', 4),     // RequesterId
            '0',                    // Status
            'Very bad things here', // ErrorMessage
            str_repeat('a', 50),    // RequestID
            $value,              // AccountID
            444444444444,           // SerialNumber
            0.00,                   // PostageBalance
            0.00,                   // AscendingBalance
            'A',                    // AccountStatus
            '222222222222'          // DeviceID
        );

        $response = RecreditRequestResponse::fromXml($xml);
        $this->assertEquals($value, $response->getCertifiedIntermediary()->getAccountId());
    }

    public function testSerialNumberAgreement()
    {
        $value = '444444444444';
        $xml = $this->getXml(
            str_repeat('4', 4),     // RequesterId
            '0',                    // Status
            'Very bad things here', // ErrorMessage
            str_repeat('a', 50),    // RequestID
            '4324325',              // AccountID
            $value,           // SerialNumber
            0.00,                   // PostageBalance
            0.00,                   // AscendingBalance
            'A',                    // AccountStatus
            '222222222222'          // DeviceID
        );

        $response = RecreditRequestResponse::fromXml($xml);
        $this->assertEquals($value, $response->getCertifiedIntermediary()->getSerialNumber());
    }

    public function testPostageBalanceAgreement()
    {
        $value = 3.20;
        $xml = $this->getXml(
            str_repeat('4', 4),     // RequesterId
            '0',                    // Status
            'Very bad things here', // ErrorMessage
            str_repeat('a', 50),    // RequestID
            '4324325',              // AccountID
            '444444444444',         // SerialNumber
            $value,                 // PostageBalance
            0.00,                   // AscendingBalance
            'A',                    // AccountStatus
            '222222222222'          // DeviceID
        );

        $response = RecreditRequestResponse::fromXml($xml);
        $this->assertEquals($value, $response->getCertifiedIntermediary()->getPostageBalance());
    }

    public function testAscendingBalanceAgreement()
    {
        $value = 1.23;
        $xml = $this->getXml(
            str_repeat('4', 4),     // RequesterId
            '0',                    // Status
            'Very bad things here', // ErrorMessage
            str_repeat('a', 50),    // RequestID
            '4324325',              // AccountID
            '444444444444',         // SerialNumber
            0.00,                 // PostageBalance
            $value,                   // AscendingBalance
            'A',                    // AccountStatus
            '222222222222'          // DeviceID
        );

        $response = RecreditRequestResponse::fromXml($xml);
        $this->assertEquals($value, $response->getCertifiedIntermediary()->getAscendingBalance());
    }

    public function testAccountStatusAgreement()
    {
        $value = 'A';
        $xml = $this->getXml(
            str_repeat('4', 4),     // RequesterId
            '0',                    // Status
            'Very bad things here', // ErrorMessage
            str_repeat('a', 50),    // RequestID
            '4324325',              // AccountID
            '444444444444',         // SerialNumber
            0.00,                 // PostageBalance
            0.00,                   // AscendingBalance
            $value,                    // AccountStatus
            '222222222222'          // DeviceID
        );

        $response = RecreditRequestResponse::fromXml($xml);
        $this->assertEquals($value, $response->getCertifiedIntermediary()->getAccountStatus());
    }

    public function testDeviceIDAgreement()
    {
        $value = '222231222222';
        $xml = $this->getXml(
            str_repeat('4', 4),     // RequesterId
            '0',                    // Status
            'Very bad things here', // ErrorMessage
            str_repeat('a', 50),    // RequestID
            '4324325',              // AccountID
            '444444444444',         // SerialNumber
            0.00,                 // PostageBalance
            0.00,                   // AscendingBalance
            'A',                    // AccountStatus
            $value          // DeviceID
        );

        $response = RecreditRequestResponse::fromXml($xml);
        $this->assertEquals($value, $response->getCertifiedIntermediary()->getDeviceId());
    }

    public function testSetSerialNumberThrowsExceptionWhenSerialNumberNotNumeric()
    {
        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $value = 'a44444444444';
        $xml = $this->getXml(
            str_repeat('4', 4),     // RequesterId
            '0',                    // Status
            'Very bad things here', // ErrorMessage
            str_repeat('a', 50),    // RequestID
            '4324325',              // AccountID
            $value,         // SerialNumber
            0.00,                 // PostageBalance
            0.00,                   // AscendingBalance
            'A',                    // AccountStatus
            '444444444444'         // DeviceID
        );

        $response = RecreditRequestResponse::fromXml($xml);
    }

    public function testSetSerialNumberThrowsExceptionWhenSerialNumberTooLong()
    {
        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $value = '5444444444445';
        $xml = $this->getXml(
            str_repeat('4', 4),     // RequesterId
            '0',                    // Status
            'Very bad things here', // ErrorMessage
            str_repeat('a', 50),    // RequestID
            '4324325',              // AccountID
            $value,         // SerialNumber
            0.00,                 // PostageBalance
            0.00,                   // AscendingBalance
            'A',                    // AccountStatus
            '444444444444'         // DeviceID
        );

        $response = RecreditRequestResponse::fromXml($xml);
    }

    public function testSetAccountStatusThrowsExceptionWhenValueInvalid()
    {
        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $value = 'invalud';
        $xml = $this->getXml(
            str_repeat('4', 4),     // RequesterId
            '0',                    // Status
            'Very bad things here', // ErrorMessage
            str_repeat('a', 50),    // RequestID
            '4324325',              // AccountID
            '544444444445',         // SerialNumber
            0.00,                 // PostageBalance
            0.00,                   // AscendingBalance
            $value,                    // AccountStatus
            '444444444444'         // DeviceID
        );

        $response = RecreditRequestResponse::fromXml($xml);
    }

    public function testSetDeviceIDThrowsExceptionWhenValueTooLong()
    {
        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $value = '43444444444444';
        $xml = $this->getXml(
            str_repeat('4', 4),     // RequesterId
            '0',                    // Status
            'Very bad things here', // ErrorMessage
            str_repeat('a', 50),    // RequestID
            '4324325',              // AccountID
            '544444444445',         // SerialNumber
            0.00,                 // PostageBalance
            0.00,                   // AscendingBalance
            'A',                    // AccountStatus
            $value         // DeviceID
        );

        $response = RecreditRequestResponse::fromXml($xml);
    }

    public function testSetAccountIDThrowsExceptionWhenEmpty()
    {
        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $value = '';
        $xml = $this->getXml(
            str_repeat('4', 4),     // RequesterId
            '0',                    // Status
            'Very bad things here', // ErrorMessage
            str_repeat('a', 50),    // RequestID
            $value,              // AccountID
            '544444444445',         // SerialNumber
            0.00,                 // PostageBalance
            0.00,                   // AscendingBalance
            'A',                    // AccountStatus
            '888888888888'         // DeviceID
        );

        $response = RecreditRequestResponse::fromXml($xml);
    }

    public function testSetAccountIDThrowsExceptionWhenValueTooLong()
    {
        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $value = '44444444';
        $xml = $this->getXml(
            str_repeat('4', 4),     // RequesterId
            '0',                    // Status
            'Very bad things here', // ErrorMessage
            str_repeat('a', 50),    // RequestID
            $value,              // AccountID
            '544444444445',         // SerialNumber
            0.00,                 // PostageBalance
            0.00,                   // AscendingBalance
            'A',                    // AccountStatus
            '888888888888'         // DeviceID
        );

        $response = RecreditRequestResponse::fromXml($xml);
    }
    private function getXml(
        $requesterId,
        $status,
        $errorMessage,
        $requestId,
        $accountId,
        $serialNumber,
        $postageBalance,
        $ascendingBalance,
        $accountStatus,
        $deviceId
    ) : string {
        return
        '<?xml version="1.0" encoding="utf-8"?>' .
        '<RecreditRequestResponse>' .
        '<Status>' . $status . '</Status>' .
        '<RequesterID>' . $requesterId . '</RequesterID>' .
        '<RequestID>' . $requestId . '</RequestID>' .
        '<CertifiedIntermediary>' .
        '<AccountID>' . $accountId . '</AccountID>' .
        '<SerialNumber>' . $serialNumber . '</SerialNumber>' .
        '<PostageBalance>' . $postageBalance . '</PostageBalance>' .
        '<AscendingBalance>' . $ascendingBalance . '</AscendingBalance>' .
        '<AccountStatus>' . $accountStatus . '</AccountStatus>' .
        '<DeviceID>' . $deviceId . '</DeviceID>' .
        '</CertifiedIntermediary>' .
        '</RecreditRequestResponse>';
    }
}
