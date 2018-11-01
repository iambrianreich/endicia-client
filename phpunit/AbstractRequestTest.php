<?php

 namespace Tests\RWC\Endicia;

use DOMDocument;
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
            /**
             * Returns the request XML common to all Endicia requests.
             *
             * Returns the request XML common to all Endicia API requests. This includes
             * the RequesterID, RequestID, and CertifiedIntermediary tags.
             *
             * @return string Returns the request XML common to all Endicia requests.
             */
            public function toXml(): string
            {
                return $this->toDOMDocument()->saveXML();
            }

            /**
             * @return DOMDocument
             */
            public function toDOMDocument(): DOMDocument
            {
                $document = new DOMDocument();
                $root = $document->createElement('RecreditRequest');
                $document->appendChild($root);

                $root->appendChild($document->createElement('RequesterID', $this->getRequesterId()));
                $root->appendChild($document->createElement('RequestID', $this->getRequestId()));
                $root->appendChild($this->getCertifiedIntermediary()->toDOMElement($document));

                return $document;
            }
        };
    }
}
