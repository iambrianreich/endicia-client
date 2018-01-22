<?php

 namespace Tests\RWC\Endicia;

use RWC\Endicia\Testing\ApiTestCase;
use RWC\Endicia\Client;
use RWC\Endicia\InvalidArgumentException;
use RWC\Endicia\CertifiedIntermediary;
use RWC\Endicia\ChangePassPhraseRequest;

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
        }
        return;

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
}
