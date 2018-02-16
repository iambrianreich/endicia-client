<?php

/**
 * This file contains the RWC\Endicia\AbstactResponse class.
 *
 * @author     Brian Reich <breich@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */
namespace RWC\Endicia;

use RWC\Endicia\InvalidArgumentException;

/**
 * Base class for Responses returned from the Endicia API.
 *
 * @author     Brian Reich <breich@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */
abstract class AbstractResponse
{
    /**
     * The 4-character requester id of the entity that made the response.
     *
     * @var string
     */
    private $requesterId;

    /**
     * The id of the request that the reponse belongs to.
     *
     * @var string
     */
    private $requestId;

    /**
     * The status code of the Response.
     *
     * @var int
     */
    private $status;

    /**
     * The error message of the Response.
     *
     * @var string
     */
    private $errorMessage;

    /**
     * Sets the requester id.
     *
     * The requester id is the unique identifier that belongs to the entity that
     * made the request. It identifies that individual or application that
     * submitted the API request and not the account that will be billed for
     * the activity.
     *
     * The requester id may not be empty and must be 4 characters in length.
     *
     * @param string $requesterId The requester id.
     * @throws InvalidArgumentException If the requester id is invalid.
     */
    public function setRequesterId(string $requesterId) : void
    {
        // Ensure there is a value.
        if (empty($requesterId)) {
            throw new InvalidArgumentException("Requester ID cannot be empty");
        }

        // Ensure the right size.
        if (strlen($requesterId) > 4) {
            throw new InvalidArgumentException("Requester ID must be 4 characters.");
        }

        $this->requesterId = $requesterId;
    }

    /**
     * Returns the requester id.
     *
     * @return Returns the requester id.
     */
    public function getRequesterId() : string
    {
        return $this->requesterId;
    }

    /**
     * Sets the request id.
     *
     * The request id is a unique id assigned to the request to which this
     * instance is the Response. If must not be empty and must be 50 or fewer
     * characters in length.
     *
     * @param string $requestId The request id.
     * @throws InvalidArgumentException if the request id is invalid.
     */
    public function setRequestId(string $requestId) : void
    {
        if (empty($requestId)) {
            throw new InvalidArgumentException("Request id cannot be empty.");
        }

        if (strlen($requestId) > 50) {
            throw new InvalidArgumentException(
                "Request id must be 50 or fewer characters."
            );
        }

        $this->requestId = $requestId;
    }

    /**
     * Returns the request id.
     * @return string Returns the request id.
     */
    public function getRequestId() : string
    {
        return $this->requestId;
    }

    /**
     * Sets the status of the Response.
     *
     * Sets the status of the Response. A status code of 0 specifies success.
     * Any other status code is an error code.
     *
     * @param int $success The request's status code.
     */
    public function setStatus(int $success) : void
    {
        $this->success = $success;
    }

    /**
     * Returns the Response's status code.
     *
     * @return int Returns the status code.
     */
    public function getStatus() : int
    {
        return $this->success;
    }

    /**
     * Returns true if the Request was a success.
     *
     * This method will return true if the Response has a status code of 0,
     * which according to the API documentation means the request was
     * successful.
     *
     * @return boolean Returns true if the request was successful.
     */
    public function isSuccessful() : bool
    {
        return $this->getStatus() == 0;
    }

    /**
     * Sets the error message for the Response.
     *
     * Sets the error message for the response. If the response represents a
     * failure, it should provide an error message that describes why the
     * request failed.
     *
     * @param string $errorMessage A description of the request failure.
     */
    public function setErrorMessage(string $errorMessage) : void
    {
        if (strlen($errorMessage) > 150) {
            throw new InvalidArgumentException(
                "Error message must be 150 or fewer characters."
            );
        }

        $this->errorMessage = $errorMessage;
    }

    /**
     * Returns the error message.
     *
     * @return string Returns the error message.
     */
    public function getErrorMessage() : string
    {
        return $this->errorMessage;
    }

    /**
     * Hydrates the base response settings from the XML payload.
     *
     * The fromXml() method will populate the status, requester id, request id,
     * and error message fields from the response.
     *
     * @param  string           $xml      The XML response.
     * @param  AbstractResponse $response The response to populate.
     *
     * @return AbstractResponse Returns the populated response.
     * @throws InvalidArgumentException if the Response is invalid.
     */
    public static function fromXml(string $xml, AbstractResponse $response = null) : AbstractResponse
    {
        // Don't accept empty XML.
        if (empty($xml)) {
            throw new InvalidArgumentException(
                "XML string is empty."
            );
        }
        
        try {
            // Get rid of the XML namespace to make DOMDocument play nice
            $xml = str_replace('www.envmgr.com/LabelService', '', $xml);
            
            // Create DOM.
            $dom = new \DOMDocument();
            $dom->loadXML($xml);

            // Get the bas properties.
            $status = $dom->getElementsByTagName("Status");
            $requesterId = $dom->getElementsByTagName("RequesterID");
            $requestId = $dom->getElementsByTagName("RequestID");
            $errorMessage = $dom->getElementsByTagName("ErrorMessage");

            // Status is required.
            if ($status->length == 0 || $status[0]->nodeValue == '') {
                throw new InvalidArgumentException("Response XML did not " .
                    "contain Status element");
            }

            if ($requesterId->length > 0) {
                $response->setRequesterId($requesterId[0]->nodeValue);
            }
            
            $response->setStatus($status[0]->nodeValue);

            if ($requestId->length > 0) {
                $response->setRequestId($requestId[0]->nodeValue);
            }

            if ($errorMessage->length > 0) {
                $response->setErrorMessage($errorMessage[0]->nodeValue);
            } else {
            	$response->setErrorMessage('');
            }
            
            return $response;
        } catch (\Exception $e) {
            throw new InvalidArgumentException(
                "Invalid ChangePassPhraseRequestResponse XML. " .
                $e->getMessage(),
                null,
                $e
            );
        }
    }
}
