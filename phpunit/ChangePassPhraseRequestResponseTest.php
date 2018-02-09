<?php

namespace Tests\RWC\Endicia;

use PHPUnit\Framework\TestCase;
use RWC\Endicia\AbstractResponse;
use RWC\Endicia\CertifiedIntermediary;
use RWC\Endicia\ChangePassPhraseRequestResponse;

class ChangePassPhraseRequestResponseTest extends TestCase
{
    public function testFromXmlAssignsToken()
    {
        $requesterId    = str_repeat('4', 4);
        $status         = '0';
        $errorMessage   = 'Very bad things here';
        $requestId = str_repeat('a', 50);
        $token = str_repeat('a', 50);
        $xml            =
        '<?xml version="1.0" encoding="utf-8"?>' .
        '<ChangePassPhraseRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
        '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
        '<RequestID>' . htmlentities($requestId) . '</RequestID>' .
        '<Status>' . $status . '</Status>' .
        '<ErrorMessage>' . $errorMessage . '</ErrorMessage>' .
        '<Token>' . htmlentities($token) . '</Token>' .
        '</ChangePassPhraseRequestResponse>';

        $response = ChangePassPhraseRequestResponse::fromXml($xml);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($token, $response->getToken());
    }
    
    public function testFromXmlSucceedsWhenTokenNotSpecified()
    {
        $requesterId    = str_repeat('4', 4);
        $status         = '0';
        $errorMessage   = 'Very bad things here';
        $requestId = str_repeat('a', 50);
        $token = str_repeat('a', 50);
        $xml            =
        '<?xml version="1.0" encoding="utf-8"?>' .
        '<ChangePassPhraseRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
        '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
        '<RequestID>' . htmlentities($requestId) . '</RequestID>' .
        '<Status>' . $status . '</Status>' .
        '<ErrorMessage>' . $errorMessage . '</ErrorMessage>' .
        '</ChangePassPhraseRequestResponse>';

        $response = ChangePassPhraseRequestResponse::fromXml($xml);
        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getToken());
    }

    public function testFromXmlDoesNotSetTokenForFailedResponse()
    {
        $requesterId    = str_repeat('4', 4);
        $status         = '444';
        $errorMessage   = 'Very bad things here';
        $requestId = str_repeat('a', 50);
        $token = str_repeat('a', 50);
        $xml            =
        '<?xml version="1.0" encoding="utf-8"?>' .
        '<ChangePassPhraseRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
        '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
        '<RequestID>' . htmlentities($requestId) . '</RequestID>' .
        '<Status>' . $status . '</Status>' .
        '<ErrorMessage>' . $errorMessage . '</ErrorMessage>' .
        '<Token>' . htmlentities($token) . '</Token>' .
        '</ChangePassPhraseRequestResponse>';

        $response = ChangePassPhraseRequestResponse::fromXml($xml);
        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getToken());
    }
    public function testResponseReturnsSetToken()
    {
        $requesterId    = str_repeat('4', 4);
        $status         = '0';
        $errorMessage   = 'Very bad things here';
        $requestId = str_repeat('a', 50);
        $token = str_repeat('a', 50);
        $xml            =
        '<?xml version="1.0" encoding="utf-8"?>' .
        '<ChangePassPhraseRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
        '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
        '<RequestID>' . htmlentities($requestId) . '</RequestID>' .
        '<Status>' . $status . '</Status>' .
        '<ErrorMessage>' . $errorMessage . '</ErrorMessage>' .
        '<Token>' . htmlentities($token) . '</Token>' .
        '</ChangePassPhraseRequestResponse>';

        $response = ChangePassPhraseRequestResponse::fromXml($xml);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($token, $response->getToken());
    }

    public function testFromXmlThrowsExceptionWhenTokenTooLong()
    {
        $requesterId    = str_repeat('4', 4);
        $status         = '0';
        $errorMessage   = 'Very bad things here';
        $requestId = str_repeat('a', 50);
        $token = str_repeat('a', 51);
        $xml            =
            '<?xml version="1.0" encoding="utf-8"?>' .
            '<ChangePassPhraseRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
            '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
            '<RequestID>' . htmlentities($requestId) . '</RequestID>' .
            '<Status>' . $status . '</Status>' .
            '<ErrorMessage>' . $errorMessage . '</ErrorMessage>' .
            '<Token>' . htmlentities($token) . '</Token>' .
            '</ChangePassPhraseRequestResponse>';

        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $response = ChangePassPhraseRequestResponse::fromXml($xml);
        $this->assertFalse($response->isSuccessful());
    }

    public function testFromXmlThrowsExceptionWhenTokenEmpty()
    {
        $requesterId    = str_repeat('4', 4);
        $status         = '0';
        $errorMessage   = 'Very bad things here';
        $requestId = str_repeat('a', 50);
        $token = '';

        $xml            =
            '<?xml version="1.0" encoding="utf-8"?>' .
            '<ChangePassPhraseRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
            '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
            '<RequestID>' . htmlentities($requestId) . '</RequestID>' .
            '<Status>' . $status . '</Status>' .
            '<ErrorMessage>' . $errorMessage . '</ErrorMessage>' .
            '<Token>' . htmlentities($token) . '</Token>' .
            '</ChangePassPhraseRequestResponse>';

        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $response = ChangePassPhraseRequestResponse::fromXml($xml);
        $this->assertFalse($response->isSuccessful());
    }
}
