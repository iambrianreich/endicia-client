<?php

 namespace Tests\RWC\Endicia;

use PHPUnit\Framework\TestCase;
use RWC\Endicia\AbstractRequest;
use RWC\Endicia\CertifiedIntermediary;

class AbstractRequestTest extends TestCase
{
    public function testRequesterIdAgreement()
    {
        $requesterId = '1234';
        $ci = CertifiedIntermediary::createFromToken('abcdef');
        $request = $this->getRequest($requesterId, $ci);

        $this->assertEquals($requesterId, $request->getRequesterId());
    }
    
    public function testRequestIdIsUnique()
    {
        $requesterId = '1234';
        $ci = CertifiedIntermediary::createFromToken('abcdef');

        $requests  = [];
        $count     = 100;
        
        for ($i = 0; $i < $count; $i++) {
            $request    = $this->getRequest($requesterId, $ci);
            $requestId  = $request->getRequestId();
            
            // Assert that the request id is not already in the array.
            $this->assertFalse(in_array($requestId, $requests));

            $requests[] = $requestId;
        }
    }

    public function testXmlContainsCertifiedIntermediary()
    {
        $requesterId = '1234';
        $ci = CertifiedIntermediary::createFromToken('abcdef');
        $xml = $this->getRequest($requesterId, $ci)->toXml();

        $this->assertRegExp('/<CertifiedIntermediary>.*<\/CertifiedIntermediary>/', $xml);
    }

    public function testXmlContainsRequesterId()
    {
        $requesterId = '1234';
        $ci = CertifiedIntermediary::createFromToken('abcdef');
        $xml = $this->getRequest($requesterId, $ci)->toXml();

        $this->assertRegExp('/<RequesterID>.*<\/RequesterID>/', $xml);
    }

    public function testXmlContainsRequestId()
    {
        $requesterId = '1234';
        $ci = CertifiedIntermediary::createFromToken('abcdef');
        $xml = $this->getRequest($requesterId, $ci)->toXml();

        $this->assertRegExp('/<RequestID>.*<\/RequestID>/', $xml);
    }

    public function getRequest(string $requesterId, CertifiedIntermediary $certifiedIntermediary)
    {
        return new class($requesterId, $certifiedIntermediary) extends AbstractRequest {
        };
    }
}
