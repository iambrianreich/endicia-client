<?php

/**
 * This file contains the RWC\Endicia\IRequestElement interface.
 *
 * @author     Brian Reich <help@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia;

use DOMElement;

/**
 * Interface for XML request elements in the Endicia Client library. Each
 * request element understands how to render itself as XML, which can be
 * returned as text using toXml() or as a DOMElement using toDOMElement().
 *
 * @package RWC\Endicia
 */
interface IRequestElement
{
    /**
     * Returns the element as an XML string.
     *
     * @return string Returns the element as an XML string.
     */
    public function toXml() : string;

    /**
     * Returns the element as a DOMElement created from a given DOM.
     *
     * @param \DOMDocument $document The DOMDocument used to create the element.
     * @return DOMElement Returns the generated DOMElement.
     */
    public function toDOMElement(\DOMDocument $document) : DOMElement;
}