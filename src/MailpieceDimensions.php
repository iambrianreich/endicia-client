<?php

/**
 * This file contains the RWC\Endicia\MailpieceDimensions class.
 *
 * @author     Joshua Stroup <josh.stroup@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia;

use DOMDocument;
use DOMElement;

class MailpieceDimensions implements IRequestElement
{
    /**
     * The length of the mail piece in inches
     *
     * @var float
     */
    protected $length;

    /**
     * The width of the mail piece in inches
     *
     * @var float
     */
    protected $width;

    /**
     * The height of the mail piece in inches
     *
     * @var float
     */
    protected $height;

    /**
     * Creates a new MailpieceDimensions node. All fields are required if using this node.
     *
     * @param float $length  The length of the mailpiece in inches.
     * @param float $width   The width of the mailpiece in inches.
     * @param float $height  The height of the mailpiece in inches.
     */
    public function __construct(
        float $length,
        float $width,
        float $height
    ) {
        $this->length = $length;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Returns the length of the mailpiece
     *
     * @return float Returns the length of the mailpiece
     */
    public function getLength(): float
    {
        return $this->length;
    }

    /**
     * Sets the length of the mail piece
     *
     * @param float $length The length of the mailpiece in inches
     */
    public function setLength(float $length): void
    {
        $this->length = $length;
    }

    /**
     * Returns the width of the mailpiece
     *
     * @return float Returns the width of the mailpiece
     */
    public function getWidth(): float
    {
        return $this->width;
    }

    /**
     * Sets the width of the mail piece
     *
     * @param float $width The width of the mailpiece in inches
     */
    public function setWidth(float $width): void
    {
        $this->width = $width;
    }

    /**
     * Returns the height of the mailpiece
     *
     * @return float Returns the height of the mailpiece
     */
    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * Sets the height of the mail piece
     *
     * @param float $height The height of the mailpiece in inches
     */
    public function setHeight(float $height): void
    {
        $this->height = $height;
    }

    /**
     * Returns the XML for MailpieceDimensions
     *
     * @return string Returns the MailpieceDimensions XML
     */
    public function toXml() : string
    {
        $document = new DOMDocument();
        $mailPieceDimensions = $this->toDOMElement($document);
        return $document->saveXML($mailPieceDimensions);
    }

    /**
     * Returns the element as a DOMElement created from a given DOM.
     *
     * @param \DOMDocument $document The DOMDocument used to create the element.
     * @return DOMElement Returns the generated DOMElement.
     */
    public function toDOMElement(\DOMDocument $document): DOMElement
    {
        $dimEl = $document->createElement('MailpieceDimensions');
        $dimEl->appendChild($document->createElement('Length', ($this->getLength())));
        $dimEl->appendChild($document->createElement('Width', $this->getWidth()));
        $dimEl->appendChild($document->createElement('Height', $this->getHeight()));

        return $dimEl;
    }
}