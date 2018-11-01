<?php

/**
 * This file contains the RWC\Endicia\IXMLRequest interface.
 *
 * @author     Brian Reich <help@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia;


use DOMDocument;

/**
 * Interface for XML requests to the Endicia Client library.
 *
 * Each request element understands how to render itself as XML, which can be
 * returned as text using toXml() or as a DOMDocument using toDOMDocument().
 *
 * @package RWC\Endicia
 */
interface IXMLRequest
{
    /**
     * Returns the IXMLRequest as an XML string.
     *
     * @return string Returns the element as an XML string.
     */
    public function toXml() : string;

    /**
     * Returns IXMLRequest as a DOMDocument
     *
     * @return DOMDocument Returns the request as a DOMDocument.
     */
    public function toDOMDocument(): DOMDocument;
}