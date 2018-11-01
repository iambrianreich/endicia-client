<?php

/**
 * This file contains the RWC\Endicia\PostageRateRequest class.
 *
 * @author     Joshua Stroup <jstroup@stroupcreative.group>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia;

use DOMDocument;

class PostageRateRequest extends AbstractRequest implements IXMLRequest
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
     * The pricing tier to use for the rate request
     *
     * @var string
     */
    protected $pricing;

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
     * Whether or not to use the automation rate
     *
     * @var bool
     */
    protected $automationRate;

    /**
     * Whether or not the package is soft pack
     *
     * @var string
     */
    protected $packageTypeIndicator;

    /**
     * Whether or not the package is machinable
     *
     * @var bool
     */
    protected $machinable;

    /**
     * Enables next or 2nd day Post Office to Addressee service
     *
     * @var string
     */
    protected $serviceLevel;

    /**
     * Allows Sunday and holiday delivery service for Priority Mail Express packages
     *
     * @var string
     */
    protected $sundayHolidayDelivery;

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
     * The date to ship the mail piece in MM/DD/YYYY format
     *
     * @var string
     */
    protected $shipDate;

    /**
     * The time to ship the mail piece in HH:MM AM or PM format
     *
     * @var string
     */
    protected $shipTime;

    /**
     * Whether or not to rate consolidator "no-postage" label
     *
     * @var bool
     */
    protected $isConsolidator;

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

    /**
     * Optional Node to include XML elements in the response
     *
     * @var ResponseOptions
     */
    protected $responseOptions;

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
        ?string $pricing,
        ?array $services,
        ?string $serviceLevel,
        ?string $sundayHolidayDelivery,
        ?string $shipDate,
        ?string $shipTime,
        ?int $dateAdvance,
        ?bool $deliveryTimeDays,
        ?bool $estimatedDeliveryDate,
        ?bool $automationRate,
        ?bool $machinable,
        ?string $packageTypeIndicator,
        ?ResponseOptions $responseOptions
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
        $this->setPricing($pricing);
        $this->setServices($services);
        $this->setServiceLevel($serviceLevel);
        $this->setSundayHolidayDelivery($sundayHolidayDelivery);
        $this->setShipDate($shipDate);
        $this->setShipTime($shipTime);
        $this->setDateAdvance($dateAdvance);
        $this->setDeliveryTimeDays($deliveryTimeDays);
        $this->setEstimatedDeliveryDate($estimatedDeliveryDate);
        $this->setAutomationRate($automationRate);
        $this->setMachinable($machinable);
        $this->setPackageTypeIndicator($packageTypeIndicator);
        $this->setResponseOptions($responseOptions);
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
     * it must be one of the following, which are available in 'RWC\Endicia\Constants::MAILCLASS_*':
     *
     * PriorityExpress
     * First
     * LibraryMail
     * MediaMail
     * ParcelSelect
     * RetailGround
     * Priority
     * PriorityMailExpressInternational
     * FirstClassMailInternational
     * FirstClassPackageInternationalService
     * PriorityMailInternational
     *
     * @param string $mailClass The mail class to set for the mail piece
     */
    public function setMailClass(string $mailClass) : void
    {
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
     * Returns the pricing tier for the mail piece
     *
     * @return string Returns the pricing tier for the mail piece
     */
    public function getPricing() : ?string
    {
        return $this->pricing;
    }

    /**
     * Sets the pricing tier for the mail piece
     * Optional, pricing is determined by the mail class if not provided
     * As of Label Server v8.8, it must be one of the following:
     *
     * CommercialBase
     * CommercialPlus
     * Retail
     *
     * @param string $pricing The pricing tier to use for the mail piece
     */
    public function setPricing(?string $pricing) : void
    {
        $this->pricing = $pricing;
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
     * Options as of Label Server v8.8 are in Constants::MAILSHAPE_*
     *
     * @param string $mailpieceShape The preset mail piece shape to use for the request
     */
    public function setMailpieceShape(?string $mailpieceShape) : void
    {
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
     * Returns whether or not to use the automation rate
     *
     * @return bool Returns true when using the automation rate
     */
    public function isAutomationRate() : ?bool
    {
        return $this->automationRate;
    }

    /**
     * Sets whether or not to use the automation rate
     * Available only for First Class Letter shaped mail pieces.
     *
     * @param bool $automationRate True to use automation rate
     */
    public function setAutomationRate(?bool $automationRate) : void
    {
        $this->automationRate = $automationRate;
    }

    /**
     * @return string
     */
    public function getPackageTypeIndicator() : ?string
    {
        return $this->packageTypeIndicator;
    }

    /**
     * Sets the package type indicator, default is null
     * Set to Softpack is using Commercial Plus cubic price for soft-pack packaging
     *
     * @param string $packageTypeIndicator Sets the package type indicator, currently only 'Softpack' is supported
     */
    public function setPackageTypeIndicator(?string $packageTypeIndicator) : void
    {
        $this->packageTypeIndicator = $packageTypeIndicator;
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
     * Returns the service level for the mail piece
     *
     * @return string Returns the service level for the mail piece
     */
    public function getServiceLevel() : ?string
    {
        return $this->serviceLevel;
    }

    /**
     * Sets the service level for the mail piece
     * As of Label Server v8.8, only 'NextDay2ndDayPOToAddressee' is supported
     *
     * @param string $serviceLevel The service level to use for the mail piece
     */
    public function setServiceLevel(?string $serviceLevel) : void
    {
        $this->serviceLevel = $serviceLevel;
    }

    /**
     * Returns the Sunday or Holiday delivery status for the mail piece
     *
     * @return string Returns the Sunday or Holiday delivery status for the mail piece
     */
    public function getSundayHolidayDelivery() : ?string
    {
        return $this->sundayHolidayDelivery;
    }

    /**
     * Sets the Sunday or Holiday delivery status for the mail piece
     * As of Label Server v8.8, the following values are supported:
     *
     * TRUE Request Sunday and Holiday Delivery Service
     * FALSE (Default) Do not deliver on Sunday or holiday
     * SUNDAY Request Sunday Delivery Service
     * HOLIDAY Request Holiday Delivery Service
     *
     * @param string $sundayHolidayDelivery The Sunday and/or Holiday delivery request of the mail piece
     */
    public function setSundayHolidayDelivery(?string $sundayHolidayDelivery) : void
    {
        $this->sundayHolidayDelivery = $sundayHolidayDelivery;
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
     * @return int Returns the postal code to send the mail piece from
     */
    public function getFromPostalCode() : int
    {
        return $this->fromPostalCode;
    }

    /**
     * Sets the postal code to send the mail piece from
     *
     * @param int $fromPostalCode The postal code to send the mail piece from
     */
    public function setFromPostalCode(int $fromPostalCode) : void
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
     * @return int Returns the postal code to send the mail piece to
     */
    public function getToPostalCode() : int
    {
        return $this->toPostalCode;
    }

    /**
     * Sets the postal code to send the mail piece to
     *
     * @param int $toPostalCode The postal code to send the mail piece to
     */
    public function setToPostalCode(int $toPostalCode) : void
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
     * Returns the ship date for the mail piece
     *
     * @return string Returns the ship date for the mail piece
     */
    public function getShipDate() : ?string
    {
        return $this->shipDate;
    }

    /**
     * Sets the ship date for the mail piece
     * It must adhere to the following format:
     *
     * MM/DD/YYYY
     *
     * @param string $shipDate
     */
    public function setShipDate(?string $shipDate) : void
    {
        $this->shipDate = $shipDate;
    }

    /**
     * Returns the time to ship the mail piece
     *
     * @return string Returns the time to ship the mail piece
     */
    public function getShipTime() : ?string
    {
        return $this->shipTime;
    }

    /**
     * Sets the time to ship the mail piece
     * It must adhere to the following format:
     *
     * HH:MM AM or HH:MM PM
     *
     * @param string $shipTime The time to ship the mail piece
     */
    public function setShipTime(?string $shipTime) : void
    {
        $this->shipTime = $shipTime;
    }

    /**
     * Returns the rate consolidator status for the mail piece
     *
     * @return bool Returns the rate consolidator status for the mail piece
     */
    public function isConsolidator() : ?bool
    {
        return $this->isConsolidator;
    }

    /**
     * Sets the rate consolidator status for the mail piece
     *
     * @param bool $isConsolidator True for rate consolidator 'no-postage' Label
     */
    public function setIsConsolidator(?bool $isConsolidator) : void
    {
        $this->isConsolidator = $isConsolidator;
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
     * Returns the ResponseOptions Node to include in the request
     *
     * @return ResponseOptions Returns the ResponseOptions Node to include in the request
     */
    public function getResponseOptions() : ?ResponseOptions
    {
        return $this->responseOptions;
    }

    /**
     * Sets the ResponseOptions Node to include in the request
     *
     * @param ResponseOptions $responseOptions The ResponseOptions Node to include in the request
     */
    public function setResponseOptions(?ResponseOptions $responseOptions) : void
    {
        $this->responseOptions = $responseOptions;
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
        $xml = new DOMDocument();
        $rateReqEl = $xml->createElement('PostageRateRequest');

        $xml->appendChild($xml->createElement('RequesterID', $this->getRequesterId()));
        $xml->appendChild($xml->createElement('RequestID', $this->getRequestId()));
        $xml->appendChild($this->getCertifiedIntermediary()->toDOMElement($xml));

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

        if ($this->getPricing() != null) {
            $rateReqEl->appendChild($xml->createElement('Pricing', $this->getPricing()));
        }

        if ($this->getServiceLevel() != null) {
            $rateReqEl->appendChild($xml->createElement('ServiceLevel', $this->getServiceLevel()));
        }

        if ($this->getSundayHolidayDelivery() != null) {
            $rateReqEl->appendChild($xml->createElement('SundayHolidayDelivery', $this->getSundayHolidayDelivery()));
        }

        if ($this->getShipDate() != null) {
            $rateReqEl->appendChild($xml->createElement('ShipDate', $this->getShipDate()));
        }

        if ($this->getShipTime() != null) {
            $rateReqEl->appendChild($xml->createElement('ShipTime', $this->getShipTime()));
        }

        if ($this->getDateAdvance() != null) {
            $rateReqEl->appendChild($xml->createElement('DateAdvance', $this->getDateAdvance()));
        }

        if ($this->isDeliveryTimeDays() != null) {
            $rateReqEl->appendChild($xml->createElement('DeliveryTimeDays', $this->isDeliveryTimeDays()));
        }

        if ($this->isEstimatedDeliveryDate() != null) {
            $rateReqEl->appendChild($xml->createElement('EstimatedDeliveryDate', $this->isEstimatedDeliveryDate()));
        }

        if ($this->isAutomationRate() != null) {
            $rateReqEl->appendChild($xml->createElement('AutomationRate', $this->isAutomationRate()));
        }

        if ($this->isMachinable() != null) {
            $rateReqEl->appendChild($xml->createElement('Machinable', $this->isMachinable()));
        }

        if ($this->getPackageTypeIndicator() != null) {
            $rateReqEl->appendChild($xml->createElement('PackageTypeIndicator', $this->getPackageTypeIndicator()));
        }

        if ($this->getMailpieceDimensions() != null) {
            $rateReqEl->appendChild($this->getMailpieceDimensions()->toDOMElement($xml));
        }

        if ($this->getResponseOptions() != null) {
            $rateReqEl->appendChild($this->getResponseOptions()->toDOMElement($xml));
        }

        return $xml;
    }
}
