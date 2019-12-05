<?php

/**
 * This file contains the RWC\Endicia\GetUserSignupResponse class.
 *
 * @author     Joshua Stroup <josh.stroup@reich-consulting.net>
 * @copyright  (C) Copyright 2019 Reich Web Consulting https://www.reich-consulting.net
 * @license    MIT
 */

namespace RWC\Endicia;

use DOMDocument;

class UserSignupRequestResponse extends AbstractResponse
{
    /**
     * @var string
     */
    protected $requesterId;

    /**
     * @var string
     */
    protected $requestId;

    /**
     * @var string
     */
    protected $confirmationNumber;

    /**
     * @var string
     */
    protected $accountId;

    /**
     * @var string
     */
    protected $token;

    public static function fromXml($xml, AbstractResponse $response = null) : AbstractResponse
    {
        // Force an object.
        $response = $response ?? new UserSignupRequestResponse();

        try {
            parent::fromXml($xml, $response);

            $xml = str_replace('www.envmgr.com/LabelService', '', $xml);
            $dom = new \DOMDocument();
            $dom->loadXML($xml);

            if (!$response->isSuccessful()) {
                return $response;
            }

            $response->setRequesterId($dom->getElementsByTagName('RequesterID')[0]->nodeValue);
            $response->setRequestId($dom->getElementsByTagName('RequestID')[0]->nodeValue);
            $response->setConfirmationNumber($dom->getElementsByTagName('ConfirmationNumber')[0]->nodeValue);
            $response->setAccountId($dom->getElementsByTagName('AccountID')[0]->nodeValue);

            // Set Token if it exists
            $token = $dom->getElementsByTagName('Token');
            if ($token->length > 0) {
                $response->setToken((string) $token[0]->nodeValue);
            }

            return $response;
        } catch (\Exception $e) {
            throw new InvalidArgumentException("Invalid UserSignupRequestResponse XML. " . $e->getMessage(), null, $e);
        }
    }

    protected function __construct()
    {
    }

    /**
     * @return string
     */
    public function getRequesterId(): string
    {
        return $this->requesterId;
    }

    /**
     * @param string $requesterId
     */
    public function setRequesterId(string $requesterId): void
    {
        $this->requesterId = $requesterId;
    }

    /**
     * @return string
     */
    public function getRequestId(): string
    {
        return $this->requestId;
    }

    /**
     * @param string $requestId
     */
    public function setRequestId(string $requestId): void
    {
        $this->requestId = $requestId;
    }

    /**
     * @return string
     */
    public function getConfirmationNumber(): string
    {
        return $this->confirmationNumber;
    }

    /**
     * @param string $confirmationNumber
     */
    public function setConfirmationNumber(string $confirmationNumber): void
    {
        $this->confirmationNumber = $confirmationNumber;
    }

    /**
     * @return string
     */
    public function getAccountId(): string
    {
        return $this->accountId;
    }

    /**
     * @param string $accountId
     */
    public function setAccountId(string $accountId): void
    {
        $this->accountId = $accountId;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }
}