<?php

namespace Tests\RWC\Endicia;

use PHPUnit\Framework\TestCase;
use RWC\Endicia\CertifiedIntermediary;

class CertifiedIntermediaryTest extends TestCase
{
    public $test;

    public function setUp()
    {
    }


    public function testCreateFromTokenReturnsIntermediary()
    {
        $credential = CertifiedIntermediary::createFromToken(
            '405050505050505050505'
        );
        $this->assertInstanceOf(CertifiedIntermediary::class, $credential);
    }

   
    public function testCreateFromCredentialsReturnsIntermediary()
    {
        $credential = CertifiedIntermediary::createFromCredentials(
            '1234567',
            'This is a passphrase'
        );

        $this->assertInstanceOf(CertifiedIntermediary::class, $credential);
    }


    public function testAccountIdAgreement()
    {
        $accountId = '1234567';
        $credential = CertifiedIntermediary::createFromCredentials(
            $accountId,
            'This is a passphrase'
        );

        $this->assertEquals($accountId, $credential->getAccountId());
    }

    public function testPassPhraseAgreement()
    {
        $accountId = '1234567';
        $passPhrase = 'This is a passphrase';
        $credential = CertifiedIntermediary::createFromCredentials(
            $accountId,
            $passPhrase
        );

        $this->assertEquals($passPhrase, $credential->getPassPhrase());
    }


    public function testTokenAgreement()
    {
        $token = 'fsdhufhsduahfuidsh34289u 8432uj89432';
        $credential = CertifiedIntermediary::createFromToken(
            $token
        );

        $this->assertEquals($token, $credential->getToken());
    }

    
    public function testSetAccountIdThrowsExceptionForEmptyString()
    {
        $this->expectException('RWC\Endicia\InvalidArgumentException');

        $accountId = '';
        $passPhrase = 'This is a passphrase';
        $credential = CertifiedIntermediary::createFromCredentials(
            $accountId,
            $passPhrase
        );
    }
    
    public function testSetAccountIdThrowsExceptionForTooLong()
    {
        $this->expectException('RWC\Endicia\InvalidArgumentException');

        $accountId = '444444444444';
        $passPhrase = 'This is a passphrase';
        $credential = CertifiedIntermediary::createFromCredentials(
            $accountId,
            $passPhrase
        );
    }

    public function testSetPassPhraseThrowsExceptionForEmptyString()
    {
        $this->expectException('RWC\Endicia\InvalidArgumentException');

        $accountId = '545435';
        $passPhrase = '';
        $credential = CertifiedIntermediary::createFromCredentials(
            $accountId,
            $passPhrase
        );
    }
    
    public function testSetPassPhraseThrowsExceptionForTooLong()
    {
        $this->expectException('RWC\Endicia\InvalidArgumentException');

        $accountId = '5555555';
        $passPhrase = str_repeat('1', 100);
        $credential = CertifiedIntermediary::createFromCredentials(
            $accountId,
            $passPhrase
        );
    }

    public function testSetTokenThrowsExceptionForEmptyString()
    {
        $this->expectException('RWC\Endicia\InvalidArgumentException');

        $token = '';
        $credential = CertifiedIntermediary::createFromToken(
            $token
        );

        $this->assertEquals($token, $credential->getToken());
    }

    public function testSetTokenThrowsExceptionForTooLong()
    {
        $this->expectException('RWC\Endicia\InvalidArgumentException');

        $token = str_repeat('0', 500);
        $credential = CertifiedIntermediary::createFromToken(
            $token
        );

        $this->assertEquals($token, $credential->getToken());
    }

    public function testToXmlWrapsInCorrectTag()
    {
        $accountId = '545435';
        $passPhrase = 'passphrase';
        $credential = CertifiedIntermediary::createFromCredentials(
            $accountId,
            $passPhrase
        );

        $xml = $credential->toXml();

        $this->assertRegExp('/^<CertifiedIntermediary>.*<\/CertifiedIntermediary>$/', $xml);

        $token = '437284238482323';
        $credential = CertifiedIntermediary::createFromToken(
            $accountId,
            $passPhrase
        );

        $xml = $credential->toXml();

        $this->assertRegExp('/^<CertifiedIntermediary>.*<\/CertifiedIntermediary>$/', $xml);
    }

    public function testToXmlContainsAccountIdForCredentials()
    {
        $accountId = '545435';
        $passPhrase = 'passphrase';
        $credential = CertifiedIntermediary::createFromCredentials(
            $accountId,
            $passPhrase
        );

        $xml = $credential->toXml();

        $this->assertRegExp('/<AccountID>' . $accountId . '<\/AccountID>/', $xml);
    }

    public function testToXmlContainsPassPhraseForCredentials()
    {
        $accountId = '545435';
        $passPhrase = 'passphrase';
        $credential = CertifiedIntermediary::createFromCredentials(
            $accountId,
            $passPhrase
        );

        $xml = $credential->toXml();

        $this->assertRegExp('/<PassPhrase>' . $passPhrase . '<\/PassPhrase>/', $xml);
    }

    public function testToXmlContainsTokenForSecurityToken()
    {
        $token = 'fdsu8fs8u98423j';
        $credential = CertifiedIntermediary::createFromToken(
            $token
        );

        $xml = $credential->toXml();

        $this->assertRegExp('/<Token>' . $token . '<\/Token>/', $xml);
    }
}
