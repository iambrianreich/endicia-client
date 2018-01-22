<?php

 namespace Tests\RWC\Endicia;

use PHPUnit\Framework\TestCase;
use RWC\Endicia\AbstractResponse;
use RWC\Endicia\CertifiedIntermediary;

class AbstractResponseTest extends TestCase
{
    public function testFromXmlThrowsExceptionWhenXMLEmpty()
    {
        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $response = AbstractResponse::fromXml('', $this->getResponse());
    }
    
    public function testFromXmlThrowsExceptionWhenStatusEmpty()
    {
        $xml =
            '<?xml version="1.0" encoding="utf-8"?>' .
            '<ChangePassPhraseRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
            '<Status></Status>' .
            '<ErrorMessage>The Certified Intermediary’s account number is invalid. Error encountered (Log ID: 28332)</ErrorMessage>' .
            '</ChangePassPhraseRequestResponse>';

        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $response = AbstractResponse::fromXml($xml, $this->getResponse());
    }

    public function testFromXmlReturnsObject()
    {
        $xml =
            '<?xml version="1.0" encoding="utf-8"?>' .
            '<ChangePassPhraseRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
            '<Status>3213</Status>' .
            '<ErrorMessage>The Certified Intermediary’s account number is invalid. Error encountered (Log ID: 28332)</ErrorMessage>' .
            '</ChangePassPhraseRequestResponse>';

        $response = AbstractResponse::fromXml($xml, $this->getResponse());
        $this->assertInstanceOf('RWC\Endicia\AbstractResponse', $response);
    }

    public function testFromXmlReturnsRequesterIdInObject()
    {
        $requesterId = '4444';
        $xml =
            '<?xml version="1.0" encoding="utf-8"?>' .
            '<ChangePassPhraseRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
            '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
            '<Status>3213</Status>' .
            '<ErrorMessage>The Certified Intermediary’s account number is invalid. Error encountered (Log ID: 28332)</ErrorMessage>' .
            '</ChangePassPhraseRequestResponse>';

        $response = AbstractResponse::fromXml($xml, $this->getResponse());
        $this->assertEquals($requesterId, $response->getRequesterId());
    }

    public function testFromXmlReturnsStatusInObject()
    {
        $requesterId = '4444';
        $status = '432432';
        $xml =
            '<?xml version="1.0" encoding="utf-8"?>' .
            '<ChangePassPhraseRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
            '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
            '<Status>' . $status . '</Status>' .
            '<ErrorMessage>The Certified Intermediary’s account number is invalid. Error encountered (Log ID: 28332)</ErrorMessage>' .
            '</ChangePassPhraseRequestResponse>';

        $response = AbstractResponse::fromXml($xml, $this->getResponse());
        $this->assertEquals($status, $response->getStatus());
    }

    public function testFromXmlReturnsErrorMessageInObject()
    {
        $requesterId    = '4444';
        $status         = '432432';
        $errorMessage   = 'Very bad things here';
        $xml            =
            '<?xml version="1.0" encoding="utf-8"?>' .
            '<ChangePassPhraseRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
            '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
            '<Status>' . $status . '</Status>' .
            '<ErrorMessage>' . htmlentities($errorMessage) . '</ErrorMessage>' .
            '</ChangePassPhraseRequestResponse>';

        $response = AbstractResponse::fromXml($xml, $this->getResponse());
        $this->assertEquals($errorMessage, $response->getErrorMessage());
    }

    public function testFromXmlReturnsRequestIDInObject()
    {
        $requesterId    = '4444';
        $status         = '432432';
        $errorMessage   = 'Very bad things here';
        $requestId = '432423432';

        $xml            =
            '<?xml version="1.0" encoding="utf-8"?>' .
            '<ChangePassPhraseRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
            '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
            '<RequestID>' . htmlentities($requestId) . '</RequestID>' .
            '<Status>' . $status . '</Status>' .
            '<ErrorMessage>' . htmlentities($errorMessage) . '</ErrorMessage>' .
            '</ChangePassPhraseRequestResponse>';

        $response = AbstractResponse::fromXml($xml, $this->getResponse());
        $this->assertEquals($requestId, $response->getRequestId());
    }

    public function testFromXmlThrowsExceptionWhenErrorMessageTooLong()
    {
        $requesterId    = '4444';
        $status         = '432432';
        $errorMessage   = 'Very bad things here';
        $requestId = '432423432';

        $xml            =
            '<?xml version="1.0" encoding="utf-8"?>' .
            '<ChangePassPhraseRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
            '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
            '<RequestID>' . htmlentities($requestId) . '</RequestID>' .
            '<Status>' . $status . '</Status>' .
            '<ErrorMessage>' . str_repeat('A', 151) . '</ErrorMessage>' .
            '</ChangePassPhraseRequestResponse>';

        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $response = AbstractResponse::fromXml($xml, $this->getResponse());
    }

    public function testFromXmlThrowsExceptionWhenRequesterIDEmpty()
    {
        $requesterId    = '';
        $status         = '432432';
        $errorMessage   = 'Very bad things here';
        $requestId = '432423432';

        $xml            =
            '<?xml version="1.0" encoding="utf-8"?>' .
            '<ChangePassPhraseRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
            '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
            '<RequestID>' . htmlentities($requestId) . '</RequestID>' .
            '<Status>' . $status . '</Status>' .
            '<ErrorMessage>' . $errorMessage . '</ErrorMessage>' .
            '</ChangePassPhraseRequestResponse>';

        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $response = AbstractResponse::fromXml($xml, $this->getResponse());
    }

    public function testFromXmlThrowsExceptionWhenRequesterTooLong()
    {
        $requesterId    = str_repeat('4', 5);
        $status         = '432432';
        $errorMessage   = 'Very bad things here';
        $requestId = '432423432';

        $xml            =
            '<?xml version="1.0" encoding="utf-8"?>' .
            '<ChangePassPhraseRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
            '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
            '<RequestID>' . htmlentities($requestId) . '</RequestID>' .
            '<Status>' . $status . '</Status>' .
            '<ErrorMessage>' . $errorMessage . '</ErrorMessage>' .
            '</ChangePassPhraseRequestResponse>';

        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $response = AbstractResponse::fromXml($xml, $this->getResponse());
    }

    public function testFromXmlThrowsExceptionWhenRequestIDEmpty()
    {
        $requesterId    = str_repeat('4', 4);
        $status         = '432432';
        $errorMessage   = 'Very bad things here';
        $requestId = '';

        $xml            =
            '<?xml version="1.0" encoding="utf-8"?>' .
            '<ChangePassPhraseRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
            '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
            '<RequestID>' . htmlentities($requestId) . '</RequestID>' .
            '<Status>' . $status . '</Status>' .
            '<ErrorMessage>' . $errorMessage . '</ErrorMessage>' .
            '</ChangePassPhraseRequestResponse>';

        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $response = AbstractResponse::fromXml($xml, $this->getResponse());
    }

    public function testFromXmlThrowsExceptionWhenRequestIDTooLong()
    {
        $requesterId    = str_repeat('4', 4);
        $status         = '432432';
        $errorMessage   = 'Very bad things here';
        $requestId = str_repeat('a', 51);

        $xml            =
            '<?xml version="1.0" encoding="utf-8"?>' .
            '<ChangePassPhraseRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
            '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
            '<RequestID>' . htmlentities($requestId) . '</RequestID>' .
            '<Status>' . $status . '</Status>' .
            '<ErrorMessage>' . $errorMessage . '</ErrorMessage>' .
            '</ChangePassPhraseRequestResponse>';

        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $response = AbstractResponse::fromXml($xml, $this->getResponse());
    }

    public function testIsSuccessfulTrueWhenStatusIsZero()
    {
        $requesterId    = str_repeat('4', 4);
        $status         = '0';
        $errorMessage   = 'Very bad things here';
        $requestId = str_repeat('a', 50);

        $xml            =
            '<?xml version="1.0" encoding="utf-8"?>' .
            '<ChangePassPhraseRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
            '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
            '<RequestID>' . htmlentities($requestId) . '</RequestID>' .
            '<Status>' . $status . '</Status>' .
            '<ErrorMessage>' . $errorMessage . '</ErrorMessage>' .
            '</ChangePassPhraseRequestResponse>';

        $response = AbstractResponse::fromXml($xml, $this->getResponse());
        $this->assertTrue($response->isSuccessful());
    }

    public function testIsSuccessfulFalseWhenStatusIsNonZero()
    {
        $requesterId    = str_repeat('4', 4);
        $status         = '5';
        $errorMessage   = 'Very bad things here';
        $requestId = str_repeat('a', 50);

        $xml            =
            '<?xml version="1.0" encoding="utf-8"?>' .
            '<ChangePassPhraseRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
            '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
            '<RequestID>' . htmlentities($requestId) . '</RequestID>' .
            '<Status>' . $status . '</Status>' .
            '<ErrorMessage>' . $errorMessage . '</ErrorMessage>' .
            '</ChangePassPhraseRequestResponse>';

        $response = AbstractResponse::fromXml($xml, $this->getResponse());
        $this->assertFalse($response->isSuccessful());
    }
    public function getResponse()
    {
        return new class() extends AbstractResponse {
        };
    }
}
