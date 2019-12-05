<?php

/**
 * This file contains the RWC\Endicia\PostageRatesRequest class.
 *
 * @author     Joshua Stroup <josh.stroup@reich-consulting.net>
 * @copyright  (C) Copyright 2019 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia;

use DOMDocument;

class PostageRatesRequest extends AbstractRequest implements IXMLRequest
{
    /**
     * The Mail Class to use for the rate request
     *
     * @var string
     */
    protected $mailClass;

    /**
     * The number of days to advance date the label. Default range is 0-7.
     *
     * @var int
     */
    protected $dateAdvance;

    /**
     * The weight of the item in ounces
     *
     * @var float
     */
    protected $weight;

    /**
     * The shape of the mail piece
     *
     * @var string
     */
    protected $mailpieceShape;

    /**
     * The dimensions of the mail piece, all values must be in inches
     *
     * @var MailpieceDimensions
     */
    protected $mailpieceDimensions;

    /**
     * Whether or not the package is machinable
     *
     * @var bool
     */
    protected $machinable;

    /**
     * Services to enable for the rate request
     *
     * @var array
     */
    protected $services;

    /**
     * The postal code to ship from
     *
     * @var string
     */
    protected $fromPostalCode;

    /**
     * The country code to ship from, not required if within US
     *
     * @var string
     */
    protected $fromCountryCode;

    /**
     * The postal code to ship to
     *
     * @var string
     */
    protected $toPostalCode;

    /**
     * The country code to ship to, not required if domestic
     *
     * @var string
     */
    protected $toCountryCode;

    /**
     * Whether to include the delivery time in days in the response
     *
     * @var bool
     */
    protected $deliveryTimeDays;

    /**
     * Whether to include the estimated delivery date in the response
     *
     * @var bool
     */
    protected $estimatedDeliveryDate;

    public function __construct(
        string $requesterId,
        CertifiedIntermediary $certifiedIntermediary,
        string $mailClass,
        float $weight,
        string $fromPostalCode,
        string $toPostalCode,
        ?string $fromCountryCode,
        ?string $toCountryCode,
        ?string $mailpieceShape,
        ?MailpieceDimensions $mailpieceDimensions,
        ?array $services,
        ?int $dateAdvance,
        ?bool $deliveryTimeDays,
        ?bool $estimatedDeliveryDate,
        ?bool $machinable
    ) {
        parent::__construct($requesterId, $certifiedIntermediary);

        $this->setMailClass($mailClass);
        $this->setWeight($weight);
        $this->setFromPostalCode($fromPostalCode);
        $this->setToPostalCode($toPostalCode);
        $this->setFromCountryCode($fromCountryCode);
        $this->setToCountryCode($toCountryCode);
        $this->setMailpieceShape($mailpieceShape);
        $this->setMailpieceDimensions($mailpieceDimensions);
        $this->setServices($services);
        $this->setDateAdvance($dateAdvance);
        $this->setDeliveryTimeDays($deliveryTimeDays);
        $this->setEstimatedDeliveryDate($estimatedDeliveryDate);
        $this->setMachinable($machinable);
    }

    /**
     * Returns the mail class for the mail piece
     *
     * @return string Returns the mail class for the mail piece
     */
    public function getMailClass() : string
    {
        return $this->mailClass;
    }

    /**
     * Sets the Mail Class to use for the rate request. As of Label Server v8.8,
     * it must be one of the following, which are available in 'RWC\Endicia\MailClass::*':
     *
     * Domestic
     * International
     *
     * @param string $mailClass The mail class to set for the mail piece
     *
     * @throws \RWC\Endicia\InvalidArgumentException
     */
    public function setMailClass(string $mailClass) : void
    {
        if (!MailClass::is_valid($mailClass)) {
            throw new InvalidArgumentException('Mail Class must be either Domestic or International');
        }

        $this->mailClass = $mailClass;
    }

    /**
     * Returns the number of days to advance date of the mail piece
     *
     * @return int
     */
    public function getDateAdvance() : ?int
    {
        return $this->dateAdvance;
    }

    /**
     * The number of days to advance the date of the mail piece
     * It is optional, but if included it must be an integer from 0-7
     *
     * @param int $dateAdvance The number of days to advance the mail piece
     */
    public function setDateAdvance(?int $dateAdvance) : void
    {
        $this->dateAdvance = $dateAdvance;
    }

    /**
     * Returns the shipping weight in ounces
     *
     * @return float Returns the shipping weight in ounces
     */
    public function getWeight() : float
    {
        return $this->weight;
    }

    /**
     * Sets the mail piece weight in ounces
     *
     * @param float $weight The mail piece weight in ounces, rounded to one decimal
     */
    public function setWeight(float $weight) : void
    {
        $this->weight = floor($weight * 10) / 10;
    }

    /**
     * Returns the shape of the mail piece
     *
     * @return string Returns the shape of the mail piece
     */
    public function getMailpieceShape() : ?string
    {
        return $this->mailpieceShape;
    }

    /**
     * Sets the mailpiece shape to one of the predefined options
     * Options as of Label Server v8.8 are in MailShape::*
     *
     * @param string $mailpieceShape The preset mail piece shape to use for the request
     *
     * @throws \RWC\Endicia\InvalidArgumentException
     */
    public function setMailpieceShape(?string $mailpieceShape) : void
    {
        if (!MailShape::is_valid($mailpieceShape) && $mailpieceShape != null) {
            throw new InvalidArgumentException('Mail Class must be one of the constants from : RWC\Endicia\MailShape');
        }

        $this->mailpieceShape = $mailpieceShape;
    }

    /**
     * Returns the MailpieceDimensions Node for the mail piece
     *
     * @return MailpieceDimensions The MailpieceDimensions Node
     */
    public function getMailpieceDimensions() : ?MailpieceDimensions
    {
        return $this->mailpieceDimensions;
    }

    /**
     * Sets the MailpieceDimensions Node
     *
     * @param MailpieceDimensions $mailpieceDimensions The MailpieceDimensions Node to include in the request
     */
    public function setMailpieceDimensions(?MailpieceDimensions $mailpieceDimensions) : void
    {
        $this->mailpieceDimensions = $mailpieceDimensions;
    }

    /**
     * Returns the machinable status of the mail piece
     *
     * @return bool Returns the machinable status of the mail piece
     */
    public function isMachinable() : ?bool
    {
        return $this->machinable;
    }

    /**
     * Sets the machinable status of the mail piece
     * Parcel Select mail pieces over 35lbs are automatically non-machinable
     *
     * @param bool $machinable
     */
    public function setMachinable(?bool $machinable) : void
    {
        $this->machinable = $machinable;
    }

    /**
     * Returns the services being requested for the mail piece
     *
     * @return array Returns the services being requested for the mail piece
     */
    public function getServices() : ?array
    {
        return $this->services;
    }

    /**
     * Sets the services being requested for the mail piece
     *
     * @param array $services The services to request services for the mail piece
     */
    public function setServices(?array $services) : void
    {
        $this->services = $services;
    }

    /**
     * Returns the postal code to send the mail piece from
     *
     * @return string Returns the postal code to send the mail piece from
     */
    public function getFromPostalCode() : string
    {
        return $this->fromPostalCode;
    }

    /**
     * Sets the postal code to send the mail piece from
     *
     * @param string $fromPostalCode The postal code to send the mail piece from
     */
    public function setFromPostalCode(string $fromPostalCode) : void
    {
        $this->fromPostalCode = $fromPostalCode;
    }

    /**
     * Returns the country code to send the mail piece from
     *
     * @return string Returns the country code to send the mail piece from
     */
    public function getFromCountryCode() : ?string
    {
        return $this->fromCountryCode;
    }

    /**
     * Sets the country code to send the mail piece from
     *
     * @param string $fromCountryCode The country code to send the mail piece from
     */
    public function setFromCountryCode(?string $fromCountryCode) : void
    {
        $this->fromCountryCode = $fromCountryCode;
    }

    /**
     * Returns the postal code to send the mail piece to
     *
     * @return string Returns the postal code to send the mail piece to
     */
    public function getToPostalCode() : string
    {
        return $this->toPostalCode;
    }

    /**
     * Sets the postal code to send the mail piece to
     *
     * @param string $toPostalCode The postal code to send the mail piece to
     */
    public function setToPostalCode(string $toPostalCode) : void
    {
        $this->toPostalCode = $toPostalCode;
    }

    /**
     * Returns the country code to send the mail piece to
     *
     * @return string Returns the country code to send the mail piece to
     */
    public function getToCountryCode() : ?string
    {
        return $this->toCountryCode;
    }

    /**
     * Sets the country code to send the mail piece to
     *
     * @param string $toCountryCode The country code to send the mail piece to
     */
    public function setToCountryCode(?string $toCountryCode) : void
    {
        $this->toCountryCode = $toCountryCode;
    }

    /**
     * Returns whether to include the delivery time in days
     *
     * @return bool Returns whether to include the delivery time in days
     */
    public function isDeliveryTimeDays() : ?bool
    {
        return $this->deliveryTimeDays;
    }

    /**
     * Sets whether to include the delivery time in days
     *
     * @param bool $deliveryTimeDays True to include delivery time in response
     */
    public function setDeliveryTimeDays(?bool $deliveryTimeDays) : void
    {
        $this->deliveryTimeDays = $deliveryTimeDays;
    }

    /**
     * Returns whether to include the estimated delivery date
     * This element relies on DeliveryTimeDays to be set to true
     *
     * @return bool Returns whether to include the estimated delivery date
     */
    public function isEstimatedDeliveryDate(): ?bool
    {
        return $this->estimatedDeliveryDate;
    }

    /**
     * Sets whether to include the estimated delivery date
     *
     * @param bool $estimatedDeliveryDate True to include the estimated delivery date in response
     */
    public function setEstimatedDeliveryDate(?bool $estimatedDeliveryDate) : void
    {
        $this->estimatedDeliveryDate = $estimatedDeliveryDate;
    }

    /**
     * Returns the XML for the PostageRateRequest
     *
     * @return string The PostageRateRequest XML
     */
    public function toXml() : string
    {
        return $this->toDOMDocument()->saveXML();
    }

    /**
     * @return DOMDocument
     */
    public function toDOMDocument(): DOMDocument
    {
        $xml = new DOMDocument('1.0', 'utf-8');
        $rateReqEl = $xml->createElement('PostageRatesRequest');

        $rateReqEl->appendChild($xml->createElement('RequesterID', $this->getRequesterId()));
        $rateReqEl->appendChild($xml->createElement('RequestID', $this->getRequestId()));
        $rateReqEl->appendChild($this->getCertifiedIntermediary()->toDOMElement($xml));

        $rateReqEl->appendChild($xml->createElement('MailClass', $this->getMailClass()));
        $rateReqEl->appendChild($xml->createElement('WeightOz', $this->getWeight()));
        $rateReqEl->appendChild($xml->createElement('FromPostalCode', $this->getFromPostalCode()));
        $rateReqEl->appendChild($xml->createElement('ToPostalCode', $this->getToPostalCode()));

        // Optional elements
        if ($this->getServices() != null) {
            $servicesEl = $xml->createElement('Services');

            foreach ($this->getServices() as $k=>$v) {
                $attr = $xml->createAttribute($k);
                $attr->value = $v;
                $servicesEl->appendChild($attr);
            }

            $rateReqEl->appendChild($servicesEl);
        }

        if ($this->getFromCountryCode() != null) {
            $rateReqEl->appendChild($xml->createElement('FromCountryCode', $this->getFromCountryCode()));
        }

        if ($this->getToCountryCode() != null) {
            $rateReqEl->appendChild($xml->createElement('ToCountryCode', $this->getToCountryCode()));
        }

        if ($this->getMailpieceShape() != null) {
            $rateReqEl->appendChild($xml->createElement('MailpieceShape', $this->getMailpieceShape()));
        }

        if ($this->getDateAdvance() != null) {
            $rateReqEl->appendChild($xml->createElement('DateAdvance', $this->getDateAdvance()));
        }

        if ($this->isEstimatedDeliveryDate() != null) {
            $rateReqEl->appendChild($xml->createElement('EstimatedDeliveryDate', ($this->isEstimatedDeliveryDate()) ? 'TRUE' : 'FALSE'));
        }

        if ($this->isDeliveryTimeDays() != null) {
            $rateReqEl->appendChild($xml->createElement('DeliveryTimeDays', ($this->isDeliveryTimeDays()) ? 'TRUE' : 'FALSE'));
        }

        if ($this->isMachinable() != null) {
            $rateReqEl->appendChild($xml->createElement('Machinable', ($this->isMachinable()) ? 'TRUE' : 'FALSE'));
        }

        if ($this->getMailpieceDimensions() != null) {
            $rateReqEl->appendChild($this->getMailpieceDimensions()->toDOMElement($xml));
        }

        // Append PostageRateRequest Node to document
        $xml->appendChild($rateReqEl);

        return $xml;
    }
}
