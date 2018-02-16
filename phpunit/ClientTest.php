<?php

 namespace Tests\RWC\Endicia;

use RWC\Endicia\Testing\ApiTestCase;
use RWC\Endicia\Address;
use RWC\Endicia\Client;
use RWC\Endicia\InvalidArgumentException;
use RWC\Endicia\CertifiedIntermediary;
use RWC\Endicia\ChangePassPhraseRequest;
use RWC\Endicia\GetPostageLabelRequest;
use RWC\Endicia\MailClass;
use RWC\Endicia\RecreditRequest;

class ClientTest extends ApiTestCase
{
    public $client;

    public function setUp()
    {
        $this->client = new Client();
    }

    public function testChangePassPhraseSucceeds()
    {
        if ($this->isSkipChangePassPhrase()) {
            $this->markTestSkipped(
                'Skipping ChangePassPhrase test so account credentials remain unchanged.'
            );
            return;
        }

        $oldPassPhrase = $this->getPassPhrase();
        $client        = new Client(Client::MODE_SANDBOX);
        $requesterId   = $client->getSandboxRequesterId();
        $ci            = $this->getCertifiedIntermediary();
        $newPassPhrase = 'totally new passphrase';
        $tokenReq      = true;
        $request       = new ChangePassPhraseRequest(
            $requesterId,
            $ci,
            $newPassPhrase,
            $tokenReq
        );
        $response = $client->changePassPhrase($request);
        $this->assertTrue($response->isSuccessful(), $response->getErrorMessage());

        $request->setPassPhrase($oldPassPhrase);
        $response = $client->changePassPhrase($request);
        $this->assertTrue($response->isSuccessful(), $response->getErrorMessage());
    }

    public function testModeAgreement()
    {
        $mode = Client::MODE_PRODUCTION;
        $this->client->setMode($mode);

        $this->assertEquals($mode, $this->client->getMode());

        $mode = Client::MODE_SANDBOX;
        $this->client->setMode($mode);

        $this->assertEquals($mode, $this->client->getMode());
    }

    public function testSetModeThrowsExceptionForInvalidMode()
    {
        $this->expectException('RWC\Endicia\InvalidArgumentException');
        $mode = 'invalid';
        $this->client->setMode($mode);
    }

    public function testGetBaseUrlReturnsProductionUrlForProductionMode()
    {
        $mode = Client::MODE_PRODUCTION;
        $this->client->setMode($mode);
        $this->assertEquals(Client::PRODUCTION_URL, $this->client->getBaseUrl());

        $mode = Client::MODE_SANDBOX;
        $this->client->setMode($mode);
        $this->assertEquals(Client::SANDBOX_URL, $this->client->getBaseUrl());
    }

    public function testRecredit()
    {
        if ($this->isSkipRecredit()) {
            $this->markTestSkipped(
                'Skipping recredit test so funds are not added to account.'
            );
            return;
        }

        $oldPassPhrase = $this->getPassPhrase();
        $client        = new Client(Client::MODE_SANDBOX);
        $requesterId   = $client->getSandboxRequesterId();
        $ci            = $this->getCertifiedIntermediary();
        $recreditAmount = 500.00;

        $request       = new RecreditRequest(
            $requesterId,
            $ci,
            $recreditAmount
        );
        
        $response = $client->recredit($request);
        $this->assertTrue($response->isSuccessful(), $response->getErrorMessage());
    }
	
	public function testGetBasicLabel()
	{
		$client = new Client(Client::MODE_SANDBOX);
        $requesterId = $client->getSandboxRequesterId();
        $ci = $this->getCertifiedIntermediary();
		$to = new Address('Jane Doe', NULL, '1 Hacker Way', NULL, 'Palo Alto', 'CA', '94025', NULL, 'US');
		$from = new Address('Jane Doe', 'Endicia, Inc.', '278 Castro Street', NULL, 'Mountain View', 'CA', '94041', NULL, 'US');
		
		$request = new GetPostageLabelRequest($requesterId, $ci, MailClass::PRIORITY, 16.0, $from, $to);
		$response = $client->getPostageLabel($request);
		$this->assertTrue($response->isSuccessful(), $response->getErrorMessage());
		
		$image = imagecreatefromstring($response->getPostageLabel());	// returns FALSE if not bitmap image data
		$this->assertThat($image, $this->logicalNot($this->isFalse()), 'Label data did not represent bitmap image');
	}
}
