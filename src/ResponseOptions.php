<?php

/**
 * This file contains the RWC\Endicia\ResponseOptions class.
 *
 * @author     Joshua Stroup <jstroup@stroupcreative.group>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia;

use DOMDocument;
use DOMElement;

class ResponseOptions implements IRequestElement
{
    /**
     * @var bool Allows the response to include postage price
     */
    protected $postagePrice;

    public function __construct(bool $postagePrice)
    {
        $this->setPostagePrice($postagePrice);
    }

    /**
     * Returns whether or not to include the PostagePrice Node in the response
     *
     * @return bool Returns whether or not to include the PostagePrice Node in the response
     */
    public function isPostagePrice(): bool
    {
        return $this->postagePrice;
    }

    /**
     * Sets whether or not to include the PostagePrice Node in the response
     *
     * @param bool $postagePrice True to include PostagePrice Node in the response
     */
    public function setPostagePrice(bool $postagePrice): void
    {
        $this->postagePrice = $postagePrice;
    }

    /**
     * @return string
     */
    public function toXml() : string
    {
        $document = new DOMDocument();
        $responseOptionsEl = $this->toDOMElement($document);
        return $document->saveXML($responseOptionsEl);
    }

    /**
     * Returns the element as a DOMElement created from a given DOM.
     *
     * @param \DOMDocument $document The DOMDocument used to create the element.
     * @return DOMElement Returns the generated DOMElement.
     */
    public function toDOMElement(\DOMDocument $document): DOMElement
    {
        $optionsEl = $document->createElement('ResponseOptions');
        $optionsEl->setAttribute('PostagePrice', (string) $this->isPostagePrice());

        return $optionsEl;
    }
}