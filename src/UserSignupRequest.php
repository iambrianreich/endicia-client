<?php

/**
 * This file contains the RWC\Endicia\UserSignupRequest class.
 *
 * @author     Joshua Stroup <josh.stroup@reich-consulting.net>
 * @copyright  (C) Copyright 2019 Reich Web Consulting https://www.reich-consulting.net
 * @license    MIT
 */

namespace RWC\Endicia;

use DOMDocument;

class UserSignupRequest extends AbstractRequest implements IXMLRequest
{
    /**
     * Whether or not to include a web token in the response
     * to use in place of a password
     *
     * @var boolean
     */
    protected $tokenRequested;

    /**
     * The First Name of the ELS Customer
     *
     * @var string
     */
    protected $firstName;

    /**
     * The Middle Name of the ELS Customer
     *
     * @var string
     */
    protected $middleName;

    /**
     * The Last Name of the ELS Customer
     *
     * @var string
     */
    protected $lastName;

    /**
     * The Title of the ELS Customer
     *
     * @var string
     */
    protected $title;

    /**
     * The Email Address of the ELS Customer
     *
     * @var string
     */
    protected $emailAddress;

    /**
     * The Phone Number of the ELS Customer
     *
     * @var string
     */
    protected $phoneNumber;

    /**
     * The Phone Extension of the ELS Customer
     *
     * @var string
     */
    protected $phoneNumberExt;

    /**
     * The Fax Number of the ELS Customer
     *
     * @var string
     */
    protected $faxNumber;

    /**
     * The type of billing you would like to use with the account
     * The only valid value currently is "TS", which will enable ELS
     *
     * @var string
     */
    protected $billingType = 'TS';

    /**
     * The Physical Address of the ELS Customer
     *
     * @var Address
     */
    protected $physicalAddress;

    /**
     * The Mailing Address (PO Box, etc.) of the ELS Customer
     *
     * @var Address
     */
    protected $mailingAddress;

    /**
     * When false, this option will disable billing for this account.
     * The customer on this account will only be able to see rates,
     * not print labels or postage.
     *
     * @var boolean
     */
    protected $paymentDetailsDeferred;

    public function __construct(string $requesterId,
                                CertifiedIntermediary $certifiedIntermediary,
                                bool $tokenRequested)
    {
        parent::__construct($requesterId, $certifiedIntermediary);

        $this->setTokenRequested($tokenRequested);
    }

    public function toXml() : string
    {
        return $this->toDOMDocument()->saveXML();
    }

    public function toDOMDocument() : DOMDocument
    {
        $xml = new DOMDocument('1.0', 'utf-8');
        $signUpReqEl = $xml->createElement('UserSignUpRequest');

        $signUpReqEl->setAttribute('TokenRequested', $this->isTokenRequested());

        $signUpReqEl->appendChild($xml->createElement('RequesterID', $this->getRequesterId()));
        $signUpReqEl->appendChild($xml->createElement('RequestID', $this->getRequestId()));

        $signUpReqEl->appendChild($xml->createElement('FirstName', $this->getFirstName()));
        $signUpReqEl->appendChild($xml->createElement('LastName', $this->getLastName()));
        if ($this->getMiddleName() != null)
            $signUpReqEl->appendChild($xml->createElement('MiddleName', $this->getMiddleName()));
        if ($this->getTitle() != null)
            $signUpReqEl->appendChild($xml->createElement('Title', $this->getTitle()));

        $signUpReqEl->appendChild($xml->createElement('EmailAddress', $this->getEmailAddress()));
        $signUpReqEl->appendChild($xml->createElement('PhoneNumber', $this->getPhoneNumber()));
        if ($this->getPhoneNumberExt() != null)
            $signUpReqEl->appendChild($xml->createElement('PhoneNumberExt', $this->getPhoneNumberExt()));
        if ($this->getFaxNumber() != null)
            $signUpReqEl->appendChild($xml->createElement('FaxNumber', $this->getFaxNumber()));

        $signUpReqEl->appendChild($xml->createElement('BillingType', $this->getBillingType()));

        $signUpReqEl->appendChild($xml->createElement('PartnerID', $this->getRequesterId()));



        $signUpReqEl->appendChild($xml->createElement('PaymentDetailsDeferred', $this->isPaymentDetailsDeferred()));

        $signUpReqEl->appendChild($xml->createElement('ICertify', 'true'));

        $xml->appendChild($signUpReqEl);

        return $xml;
    }

    /**
     * @return bool
     */
    public function isTokenRequested(): bool
    {
        return $this->tokenRequested;
    }

    /**
     * @param bool $tokenRequested
     */
    public function setTokenRequested(bool $tokenRequested): void
    {
        $this->tokenRequested = $tokenRequested;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getMiddleName(): string
    {
        return $this->middleName;
    }

    /**
     * @param string $middleName
     */
    public function setMiddleName(string $middleName): void
    {
        $this->middleName = $middleName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress(string $emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getPhoneNumberExt(): string
    {
        return $this->phoneNumberExt;
    }

    /**
     * @param string $phoneNumberExt
     */
    public function setPhoneNumberExt(string $phoneNumberExt): void
    {
        $this->phoneNumberExt = $phoneNumberExt;
    }

    /**
     * @return string
     */
    public function getFaxNumber(): string
    {
        return $this->faxNumber;
    }

    /**
     * @param string $faxNumber
     */
    public function setFaxNumber(string $faxNumber): void
    {
        $this->faxNumber = $faxNumber;
    }

    /**
     * @return string
     */
    public function getBillingType(): string
    {
        return $this->billingType;
    }

    /**
     * @param string $billingType
     */
    public function setBillingType(string $billingType): void
    {
        $this->billingType = $billingType;
    }

    /**
     * @return Address
     */
    public function getPhysicalAddress(): Address
    {
        return $this->physicalAddress;
    }

    /**
     * @param Address $physicalAddress
     */
    public function setPhysicalAddress(Address $physicalAddress): void
    {
        $this->physicalAddress = $physicalAddress;
    }

    /**
     * @return Address
     */
    public function getMailingAddress(): Address
    {
        return $this->mailingAddress;
    }

    /**
     * @param Address $mailingAddress
     */
    public function setMailingAddress(Address $mailingAddress): void
    {
        $this->mailingAddress = $mailingAddress;
    }

    /**
     * @return bool
     */
    public function isPaymentDetailsDeferred(): bool
    {
        return $this->paymentDetailsDeferred;
    }

    /**
     * @param bool $paymentDetailsDeferred
     */
    public function setPaymentDetailsDeferred(bool $paymentDetailsDeferred): void
    {
        $this->paymentDetailsDeferred = $paymentDetailsDeferred;
    }
}