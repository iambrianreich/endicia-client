<?php

 namespace Tests\RWC\Endicia;

use RWC\Endicia\Testing\ApiTestCase;
use RWC\Endicia\ChangePassphraseRequest;
use RWC\Endicia\CertifiedIntermediary;
use RWC\Endicia\InvalidArgumentException;

class ChangePassphraseRequestTest extends ApiTestCase
{
    private $requesterId = '1234';

    public function testTokenRequestedAgreement()
    {
        $ci = CertifiedIntermediary::createFromToken('abcdef');
        $passphrase = 'new passphrase';
        $request = new ChangePassPhraseRequest($this->requesterId, $ci, $passphrase);
        $request->setTokenRequested(false);
        $this->assertFalse($request->getTokenRequested());

        $request->setTokenRequested(true);
        $this->assertTrue($request->getTokenRequested());
    }

    public function testXmlTokenRequestedAttribute()
    {
        $ci = CertifiedIntermediary::createFromToken('abcdef');
        $passphrase = 'new passphrase';
        $request = new ChangePassPhraseRequest($this->requesterId, $ci, $passphrase);
        $request->setTokenRequested(false);
        $this->assertXPathExists($request->toXml(), "/ChangePassPhraseRequest[@TokenRequested='false']");
        
        $request->setTokenRequested(true);
        $this->assertXPathExists($request->toXml(), "/ChangePassPhraseRequest[@TokenRequested='true']");
    }

    public function testNewPassPhraseAgreement()
    {
        $ci = CertifiedIntermediary::createFromToken('abcdef');
        $passphrase = 'new passphrase';
        $request = new ChangePassPhraseRequest($this->requesterId, $ci, $passphrase);
        $this->assertEquals($passphrase, $request->getNewPassPhrase());
    }

    public function testXmlContainsNewPassPhrase()
    {
        $ci = CertifiedIntermediary::createFromToken('fdsfsd');
        $request = new ChangePassPhraseRequest($this->requesterId, $ci, 'This');
        $request->setTokenRequested(false);
        $this->assertXPathExists($request->toXml(), '/ChangePassPhraseRequest/NewPassPhrase');
    }

    public function testEmptyPassPhraseCausesException()
    {
        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $ci = CertifiedIntermediary::createFromToken('fdsfsd');
        $request = new ChangePassPhraseRequest($this->requesterId, $ci, '');
    }

    public function testPassphraseOver64CharactersCausesException()
    {
        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $ci = CertifiedIntermediary::createFromToken('fdsfsd');
        $passphrase = str_repeat('1', 65);
        $request = new ChangePassPhraseRequest($this->requesterId, $ci, $passphrase);
    }
}
