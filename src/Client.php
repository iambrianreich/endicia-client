<?php

/**
 * This file contains the RWC\Endicia\Client class.
 *
 * @author     Brian Reich <breich@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia;

use RWC\Endicia\Exception;
use RWC\Endicia\InvalidArgumentException;
use GuzzleHttp\Client as GuzzleClient;

/**
 * Endicia API Client.
 *
 * @author     Brian Reich <breich@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */
class Client
{
    /**
     * Production Mode.
     *
     * @var  string
     */
    const MODE_PRODUCTION = 'production';

    /**
     * Sandbox Mode.
     *
     * @var  string
     */
    const MODE_SANDBOX = 'sandbox';

    /**
     * The URL of the production API.
     *
     * @var  string
     */
    const PRODUCTION_URL = 'https://labelserver.endicia.com/LabelService/EwsLabelService.asmx';

    /**
     * The URL of the sandbox API.
     *
     * @var  string
     */
    const SANDBOX_URL = 'https://elstestserver.endicia.com/LabelService/EwsLabelService.asmx';

    /**
     * The requester ID used for all sandbox accounts.
     */
    const SANDBOX_REQUESTER_ID = 'lxxx';

    /**
     * The production mode of the client.
     *
     * @var string
     */
    private $mode;

    /**
     * The Guzzle HTTP client.
     *
     * @var GuzzleClient;
     */
    private $client;

    /**
     * Creates a new Endicia Client.
     *
     * @param string $mode The run mode. Should be one of MODE_* constants.
     * @throws  EndiciaException if client creation fails.
     */
    public function __construct($mode = 'production')
    {
        $this->setMode($mode);
    }

    public function getSandboxRequesterId() : string
    {
        return self::SANDBOX_REQUESTER_ID;
    }

    /**
     * Changes an account's pass phrase.
     *
     * The changePassPhrase request is used to change an account's pass phrase.
     * The new pass phrase is specified by the request.  If a security token
     * is desired instead, the request can specify the token requested option
     * as true. This will result in a security token being returned which can
     * be used in place of the account's account id and pass phrase to make
     * subsequent requests.
     *
     * @param  ChangePassPhraseRequest $request The request object.
     *
     * @return ChangePassPhraseRequestResponse Returns the API response.
     *
     * @throws \RWC\Endicia\EndiciaException
     * @throws \RWC\Endicia\InvalidArgumentException
     */
    public function changePassPhrase(ChangePassPhraseRequest $request)
    {
        $this->applySandboxOptions($request);

        $response = $this->getClient()->post(
            $this->getBaseUrl() . '/ChangePassPhraseXML?op=ChangePassPhraseXML',
            [
                'form_params' => [
                    'changePassPhraseRequestXML' => $request->toXml()
                ]
            ]
        );

        if ($response->getReasonPhrase() != 'OK') {
            // TODO WTF?
        }

        return ChangePassPhraseRequestResponse::fromXml((string) $response->getBody());
    }

    /**
     * @param ResetSuspendedAccountRequest $request
     * @return ResetSuspendedAccountResponse
     * @throws \RWC\Endicia\InvalidArgumentException
     */
    public function resetSuspendedAccount(ResetSuspendedAccountRequest $request) : ResetSuspendedAccountResponse
    {
        $this->applySandboxOptions($request);
        $response = $this->getClient()->post(
            $this->getBaseUrl() . '/ResetSuspendedAccountXML?op=resetSuspendedAccountXML',
            [
                'form_params' => [
                    'resetSuspendedAccountRequestXML'=> $request->toXml()
                ]
            ]
        );

        if ($response->getReasonPhrase() != 'OK') {
            // TODO WTF?
        }

        return ResetSuspendedAccountResponse::fromXml((string) $response->getBody());
    }
    /**
     * Requests a postage rate given a set of parameters
     *
     * @param PostageRateRequest $request The request object
     *
     * @return AbstractResponse Returns the API response
     *
     * @throws \RWC\Endicia\EndiciaException
     * @throws \RWC\Endicia\InvalidArgumentException
     */
    public function postageRateRequest(PostageRateRequest $request)
    {
        $this->applySandboxOptions($request);

        $response = $this->getClient()->post(
            $this->getBaseUrl() . '/CalculatePostageRateXML?op=CalculatePostageRateXML',
            [
                'form_params' => [
                    'postageRateRequestXML' => $request->toXml()
                ]
            ]
        );

        return PostageRateRequestResponse::fromXml((string) $response->getBody());
    }
    
    /**
     * Adds funds to a postage account.
     *
     * @param RecreditRequest $request The request object.
     *
     * @return RecreditRequestResponse Returns the API response.
     *
     * @throws \RWC\Endicia\EndiciaException
     * @throws \RWC\Endicia\InvalidArgumentException
     */
    public function recredit(RecreditRequest $request)
    {
        $this->applySandboxOptions($request);

        $response = $this->getClient()->post(
            $this->getBaseUrl() . '/BuyPostageXML',
            [
                'form_params' => [
                    'recreditRequestXML'=> $request->toXml()
                ]
            ]
        );

        if ($response->getReasonPhrase() != 'OK') {
            // TODO probably toss a CommunicationException instance
        }

        return RecreditRequestResponse::fromXml((string) $response->getBody());
    }
    
    /**
     * Gets a postage label
     *
     * @param  GetPostageLabelRequest $request The request object.
     *
     * @return GetPostageLabelRequestResponse Returns the API response.
     *
     * @throws \RWC\Endicia\EndiciaException
     * @throws \RWC\Endicia\InvalidArgumentException
     */
    public function getPostageLabel(GetPostageLabelRequest $request)
    {
        $this->applySandboxOptions($request);

        $response = $this->getClient()->post(
            $this->getBaseUrl() . '/GetPostageLabelXML',
            [
                'form_params' => [
                    'labelRequestXML'=> $request->toXml()
                ]
            ]
        );

        if ($response->getReasonPhrase() != 'OK') {
            // TODO WTF?
        }

        return GetPostageLabelRequestResponse::fromXml((string) $response->getBody());
    }

    /**
     * Sets the Guzzle Client.
     *
     * Sets the Guzzle Client. By setting your own Client, you can force the
     * API Client to use specific HTTP settings. If you don't set a Guzzle
     * client, a default Client instance will be created and used.
     *
     * @param GuzzleClient $client The Guzzle Client to use to make API calls.
     */
    public function setClient(GuzzleClient $client) : void
    {
        $this->client = $client;
    }

    /**
     * Returns the Guzzle Client used to make API calls.
     *
     * If no Guzzle Client has been set through the setClient() method, a
     * default instance will be created and returned.
     *
     * @return GuzzleClient
     */
    public function getClient() : GuzzleClient
    {
        if (is_null($this->client)) {
            $this->client = new GuzzleClient();
        }

        return $this->client;
    }

    /**
     * Sets the client's mode.
     *
     * Sets the client's mode. This can be used to make the Client communicate
     * with the production or sandbox versions of the Endicia API. The value
     * must either "production" or "sandbox".
     *
     * @param string $mode The run mode for the Client.
     */
    public function setMode(string $mode = self::MODE_PRODUCTION) : void
    {
        if (! $this->isValidMode($mode)) {
            throw new InvalidArgumentException('Invalid run mode ' . $mode);
        }

        $this->mode = $mode;
    }

    /**
     * Returns the client mode (production or sandbox).
     *
     * @return string Returns the client's mode.
     */
    public function getMode() : string
    {
        return $this->mode;
    }

    /**
     * Returns true if sandbox mode is enabled.
     *
     * @return boolean Returns true if Sandbox mode is enabled.
     */
    public function isSandbox() : bool
    {
        return $this->getMode() == self::MODE_SANDBOX;
    }

    /**
     * Returns the base URL for API requests.
     *
     * The base URL that is returned is dependent on the mode assigned to the
     * client. Use the setMode() method or pass a mode to the constructor to
     * switch between Sandbox and Production modes.
     *
     * @return string Returns the base URL for API requests.
     */
    public function getBaseUrl() : string
    {
        if (self::MODE_PRODUCTION == $this->getMode()) {
            return $this->getProductionBaseUrl();
        } else {
            return $this->getSandboxBaseUrl();
        }
    }

    /**
     * Returns true if the mode value is a valid Client mode.
     *
     * The mode value is valid if it equals one of the MODE_* constant values
     * defined on the Client class.
     *
     * @param  string  $mode The mode string to check.
     * @return boolean Returns true if the mode string is valid.
     */
    protected function isValidMode(string $mode) : bool
    {
        return in_array($mode, [ self::MODE_SANDBOX, self::MODE_PRODUCTION]);
    }

    /**
     * Returns the base URL for requests to the Production API.
     *
     * @return string Returns the base URL for the Production API.
     */
    protected function getProductionBaseUrl() : string
    {
        return self::PRODUCTION_URL;
    }

    /**
     * Returns the base URL for requests to the Sandbox API.
     *
     * @return string Returns the sandbox URL base.
     */
    protected function getSandboxBaseUrl() : string
    {
        return self::SANDBOX_URL;
    }

    /**
     * Checks if the Client is in sandbox mode and changes request appropriately.
     *
     * If the Client is in sandbox mode, the request will be modified to use the
     * requester id that all sandbox API requests require.
     *
     * @param  AbstractRequest $request The request.
     */
    private function applySandboxOptions(AbstractRequest $request) : void
    {
        // Drop in requester id for sandbox
        if ($this->isSandbox()) {
            $request->setRequesterId($this->getSandboxRequesterId());
        }
    }
}
