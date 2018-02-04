<?php

 namespace Tests\RWC\Endicia;

use RWC\Endicia\Testing\ApiTestCase;
use RWC\Endicia\RecreditRequest;
use RWC\Endicia\CertifiedIntermediary;
use RWC\Endicia\InvalidArgumentException;

class RecreditRequestTest extends ApiTestCase
{
    private $requesterId = '1234';

    public function testRecreditAmountAgreement()
    {
        $ci = CertifiedIntermediary::createFromToken('abcdef');
        $amount = 12.50;
        $request = new RecreditRequest(
            $this->requesterId,
            $ci,
            $amount
        );

        $this->assertEquals($amount, $request->getRecreditAmount());
    }

    public function testRecreditAmountThrowsExeptionUnderTenDollars()
    {
        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $ci = CertifiedIntermediary::createFromToken('abcdef');
        $amount = 9.99;
        $request = new RecreditRequest(
            $this->requesterId,
            $ci,
            $amount
        );
    }


    public function testRecreditAmountThrowsExeptionAtOneHundredThousandOrMore()
    {
        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $ci = CertifiedIntermediary::createFromToken('abcdef');
        $amount = 100000;
        $request = new RecreditRequest(
            $this->requesterId,
            $ci,
            $amount
        );
    }

    public function testRecreditAmountWorksForTenDollars()
    {
        $ci = CertifiedIntermediary::createFromToken('abcdef');
        $amount = 10.00;
        $request = new RecreditRequest(
            $this->requesterId,
            $ci,
            $amount
        );

        $this->assertEquals($amount, $request->getRecreditAmount());
    }

    public function testRecreditAmountWorksAtTopValidAmount()
    {
        $ci = CertifiedIntermediary::createFromToken('abcdef');
        $amount = 99999.99;
        $request = new RecreditRequest(
            $this->requesterId,
            $ci,
            $amount
        );

        $this->assertEquals($amount, $request->getRecreditAmount());
    }

    public function testToXmlContainsRecreditAmount()
    {
        $ci = CertifiedIntermediary::createFromToken('abcdef');
        $amount = 99999.99;
        $request = new RecreditRequest(
            $this->requesterId,
            $ci,
            $amount
        );

        $xml = $request->toXml();
        $this->assertXPathExists($request->toXml(), "/RecreditRequest/RecreditAmount");
        $this->assertTrue(1 == preg_match('/99999\.99/', $request->toXml()));
    }
}
