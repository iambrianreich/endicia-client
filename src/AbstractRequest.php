<?php

/**
 * This file contains the RWC\Endicia\AbstractRequest class.
 *
 * @author     Brian Reich <breich@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */
namespace RWC\Endicia;

use RWC\Endicia\InvalidArgumentException;

/**
 * Base class for Endicia API requests.
 *
 * Every request must have a requester id (partner id), which specifies the
 * unique id of the entity making the API request. This identifies and
 * distinguishes the entity or application making the request from the entity
 * that will be billed for services.
 *
 * The requester id cannot be empty and must be a 4-character string. If this
 * requirement is not met, an InvalidArgumentException is thrown at the time
 * of instantiation.
 *
 * @author     Brian Reich <breich@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */
abstract class AbstractRequest
{
    /**
     * The requester id (partner id) of the entity making the request.
     *
     * @var string
     */
    protected $requesterId;

    /**
     * The unique id of the request.
     *
     * @var string
     */
    protected $requestId;

    /**
     * The CertifiedIntermediary used to authenticate the request.
     *
     * @var CertifiedIntermediary
     */
    protected $certifiedIntermediary;

    /**
     * Creates a new AbstractRequest.
     *
     * The requester id (partner id) is required.
     *
     * @param string $requesterId The requester id (partner id);
     *
     * @throws  InvalidArgumentException If the requester id is invalid.
     */
    public function __construct(string $requesterId, CertifiedIntermediary $certifiedIntermediary)
    {
        $this->setRequesterId($requesterId);
        $this->setCertifiedIntermediary($certifiedIntermediary);
    }

    /**
     * Sets the CertifiedIntermediary used to authenticate the request.
     *
     * @param CertifiedIntermediary $certifiedIntermediary The CertifiedIntermediary.
     *
     * @throws InvalidArgumentException if a null value is set.
     */
    public function setCertifiedIntermediary(CertifiedIntermediary $certifiedIntermediary) : void
    {
        $this->certifiedIntermediary = $certifiedIntermediary;
    }

    /**
     * Returns the CertifiedIntermediary used to authenticate the request.
     *
     * @return CertifiedIntermediary Returns the CertifiedIntermediary.
     */
    public function getCertifiedIntermediary() : CertifiedIntermediary
    {
        return $this->certifiedIntermediary;
    }

    /**
     * Returns the unique id of this request.
     *
     * The request's unique id is generated using PHP's uniqid() function, which
     * will generate a random code that will be unique enough for the purposes
     * of identifying unique requests in the API.
     *
     * When a request is made, the response that is returned will specify the
     * same requestId so that requests and responses can be mapped together.
     *
     * @return string Returns the request id.
     */
    public function getRequestId() : string
    {
        if ($this->requestId == null) {
            $this->requestId = uniqid();
        }

        return $this->requestId;
    }

    /**
     * Sets the requester id (partner id)
     *
     * The requester id, or partner id, is a four-character identifier used to
     * identify the partner making the request. This is used to distinguish the
     * entity sending the request to the API from the entity that will actually
     * be billed for the service.
     *
     * The requester id must not be empty and must be a 4 character length
     * string. If it does not meet this criteria an InvalidArgumentException
     * is thrown.
     *
     * @param string $requesterId The requester id.
     *
     * @throws InvalidArgumentException if the requester id if invalid.
     */
    public function setRequesterId(string $requesterId) : void
    {
        $this->requesterId = $requesterId;
    }

    /**
     * Return the requesterId.
     *
     * @return string Returns the requesterId.
     */
    public function getRequesterId() : string
    {
        return $this->requesterId;
    }

    /**
     * Returns the request XML common to all Endicia requests.
     *
     * Returns the request XML common to all Endicia API requests. This includes
     * the RequesterID, RequestID, and CertifiedIntermediary tags.
     *
     * @return string Returns the request XML common to all Endicia requests.
     */
    public function toXml() : string
    {
        $xml = new \DOMDocument();

        $requesterIdEl = $xml->createElement('RequesterID', htmlentities($this->getRequesterId()));
        $xml->appendChild($requesterIdEl);

        $requestIdEl = $xml->createElement('RequestID', htmlentities($this->getRequestId()));
        $xml->appendChild($requestIdEl);

        $ciEl = $xml->importNode(\DOMDocument::loadXML($this->getCertifiedIntermediary()->toXml()));
        $xml->appendChild($ciEl);

//        $xml = sprintf(
//            '<RequesterID>%s</RequesterID>',
//            htmlentities($this->getRequesterId())
//        );
//        $xml .= sprintf(
//            '<RequestID>%s</RequestID>',
//            htmlentities($this->getRequestId())
//        );
//
//        $xml .= $this->getCertifiedIntermediary()->toXml();

        return $xml->saveXML();
    }
}
