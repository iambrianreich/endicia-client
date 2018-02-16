<?php

/**
 * This file contains the RWC\Endicia\GetPostageLabelRequest class.
 *
 * @author     Tom Egan <tom@tomegan.tech>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia;

use RWC\Endicia\AbstractRequest;
use RWC\Endicia\Address;
use RWC\Endicia\InvalidArgumentException;
use RWC\Endicia\MailClass;

/**
 *	A GetPostageLabelRequest is used to fetch a printable postage label.
 *
 *	The GetPostageLabelRequest requires the basic request parameters
 *		required by all Endicia requests: a requester id and a
 *		CertifiedIntermediary (authentication credentials) which will
 *		actually be charged for the activity.
 *
 *	The GetPostageLabelRequest also requires the mail class, the mailpiece
 *		weight (in ounces), the From/Return Address and the To/Destination
 *		Address
 *
 * @author     Tom Egan <tom@tomegan.tech>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */
class GetPostageLabelRequest extends AbstractRequest
{

	/**
	 *	The mail class for the mailpiece the requested label will be affixed to
	 *
	 *	@var string (Must be one of the constants from RWC\Endicia\MailClass)
	 */
	private $mailClass;

	/**
	 *	The weight of the mailpiece the requested label will be affixed to
	 *
	 *	@var float
	 */
	private $weight;

	/**
	 *	The destination for the mailpiece the requested label will be affixed to
	 *
	 *	@var Address
	 */
	private $returnAddress;

	/**
	 * The destination for the mailpiece the requested label will be affixed to
	 *
	 * @var Address
	 */
	private $destinationAddress;

	/**
	 *	Flag signaling that a Certified Mail Label is requested
	 *
	 *	@var bool
	 */
	private $useCertifiedMail;
	
	/**
	 *	Flag signaling that a Destination Confirm Mail Label is requested
	 *
	 *	@var bool
	 */
	private $useDesinationConfirmMail;

	/**
	 *	The size label to be requested
	 *
	 *	@var string (Must be one of the constants from RWC\Endicia\LabelSize)
	 */
	private $labelSize;

//	private $imageFormat;
//	private $shape;
//	private $originPostalCode;

	/**
	 *	Creates a new LabelRequest.
	 *
	 *	The request must specify the requester id which identifies the
	 *	application making the request. The CertifiedIntermediary is the
	 *	authentication token that provides either the account id and password, or
	 *	the security token for the account. The mail class specifies the handling
	 *	priority for the mailpiece the label will be affixed to, the weight
	 *	specifies the weight of the mailpiece the label will be affixed to in
	 *	ounces to the nearest 1/10th ounce, the return address specifies the
	 *	return address to be shown on the label, typically but not necessarily
	 *	the origin address for the mailpiece and the destination address
	 *	specifies the "to" address to be shown on the label.
	 *
	 *	If these validation requirements are not met, an InvalidArgumentException
	 *	is thrown.
	 *
	 *	@param string                $requesterId           The id of the requester.
	 *	@param CertifiedIntermediary $certifiedIntermediary The authentication token.
	 *
	 *	@throws InvalidArgumentException if the recredit amount is invalid.
	 */
	public function __construct(
		string $requesterId,
		CertifiedIntermediary $certifiedIntermediary,
		string $mailClass,
		float $weight,
		Address $returnAddress,
		Address $destinationAddress
	) {
		parent::__construct($requesterId, $certifiedIntermediary);
		// poking a few ivar directly so that validation starts in a valid state
		$this->useCertifiedMail = false;
		$this->useDesinationConfirmMail = false;
		$this->labelSize = NULL;

		// set ivars using accessors for specified params
		$this->setMailClass($mailClass);
		$this->setWeight($weight);
		$this->setReturnAddress($returnAddress);
		$this->setDestinationAddress($destinationAddress);
	}

	/**
	 *	Sets the Mail Class to be specified on the label for a mailpiece
	 *
	 *	@param mailClass must be a string which corresponds to one of the
	 *		public constants exposed by RWC\Endicia\MailClass
	 */
	public function setMailClass(string $mailClass) : void
	{
		if(!MailClass::is_valid($mailClass))
		{
			throw new InvalidArgumentException(
				'Mail Class must be one of the constants from : RWC\Endicia\MailClass'
			);
		}
		
		$this->mailClass = $mailClass;
	}
	
	/**
	 *	Gets the Mail Class
	 *
	 *	@return a string which corresponds to one of the public constants
	 *		exposed by RWC\Endicia\MailClass
	 */
	public function getMailClass() : string
	{
		return $this->mailClass;
	}
	
	/**
	 *	Set whether a Certified Mail Label is requested
	 *
	 *	@param bool $use - pass in true to request a Certified Mail label
	 *		or false to request a label based on Mail Class, the default
	 *
	 *	@throws InvalidArgumentException if a Destination Confirm Mail Label
	 *		has been requested or if the requested label size has been set to
	 *		a size not supported by Certified Mail
	 */
	public function setUseCertifiedMail(bool $use) : void
	{
		if($this->useDesinationConfirmMail && $use)
		{
			throw new InvalidArgumentException(
				'Mail can not be both Certified Mail and Destination Confirm Mail'
			);
		}
		
		if($this->$labelSize && !LabelSize::is_valid_certified_mail_label_size($this->$labelSize))
		{
			throw new InvalidArgumentException(
				'Requested label size not available for use with Certified Mail'
			);
		}
		
		$this->useCertifiedMail = $use;
	}
	
	/**
	 *	Get whether a Certified Mail Label is requested
	 *
	 *	@return true if a Certified Mail Label has been requested and false
	 *		otherwise
	 */
	public function getUseCertifiedMailMail() : bool
	{
		return $this->useCertifiedMail;
	}
	
	/**
	 *	Set whether a Destination Confirm Mail Label is requested
	 *
	 *	@param bool $use - pass in true to request Destination Confirm mail
	 *		or false to request a label based on Mail Class, the default
	 *
	 *	@throws InvalidArgumentException if a certified Mail Label has been
	 *		requested or if the requested label size has been set to a size
	 *		not supported by Destination confirm mail
	 */
	public function setUseDesinationConfirmMail(bool $use) : void
	{
		if($this->useCertifiedMail && $use)
		{
			throw new InvalidArgumentException(
				'Mail can not be both Certified Mail and Destination Confirm Mail'
			);
		}
		
		if($this->$labelSize && !LabelSize::is_valid_destination_confirm_label_size($this->$labelSize))
		{
			throw new InvalidArgumentException(
				'Requested label size not available for use with Destination Confirm Mail'
			);
		}
		
		$this->useDesinationConfirmMail = $use;
	}
	
	/**
	 *	Get whether a Destination Confirm Label is requested
	 *
	 *	@return true if a Destination Confirm Label has been requested
	 *		and false otherwise
	 */
	public function getUseDestinationConfirmMail() : bool
	{
		return $this->useDesinationConfirmMail;
	}
	
	/**
	 *	Sets the weight to be specified on the label for a mailpiece
	 *
	 *	@param weight a floating point number w; 0.0 < w < 1120.0 with a precision
	 *		of 1/10th ounce
	 */
	public function setWeight(float $weight) : void
	{
		if($weight < 0.0)
		{
			throw new InvalidArgumentException(
				'Weight must be greater than 0.0 ounces'
			);
		}
		
		if($weight > 1120.0)
		{
			throw new InvalidArgumentException(
				'Weight must not be greater than 1120.0 ounces (70 pounds)'
			);
		}
		
		$this->weight = $weight;
	}
	
	/**
	 *	Gets the weight to be specified on the label for a mailpiece
	 *
	 *	@return a floating point number w; 0.0 < w < 1120.0 with a precision
	 *		of 1/10th ounce
	 */
	public function getWeight() : float
	{
		return $this->weight;
	}
	
	/**
	 *	Sets the return (From) Address
	 *
	 *	@param address must be an instance of RWC\Endicia\Address
	 */
	public function setReturnAddress(Address $address) : void
	{
		if(!($address instanceof Address))
		{	// validation of the address handled by the RWC\Endicia\Address class
			throw new InvalidArgumentException(
				'Return address must be an instance of RWC\Endicia\Address'
			);
		}
		
		$this->returnAddress = $address;
	}

	/**
	 *	Gets the return (From) Address
	 *
	 *	@return an instance of RWC\Endicia\Address which encapsulates the
	 *		return (From) address for a mailpiece
	 */
	public function getReturnAddress() : Address
	{
		return $this->returnAddress;
	}
	
	/**
	 *	Sets the destination (To) Address
	 *
	 *	@param address must be an instance of RWC\Endicia\Address
	 */
	public function setDestinationAddress(Address $address) : void
	{
		if(!($address instanceof Address))
		{	// validation of the address handled by the RWC\Endicia\Address class
			throw new InvalidArgumentException(
				'Destination address must be an instance of RWC\Endicia\Address'
			);
		}
		
		$this->destinationAddress = $address;
	}

	/**
	 *	Gets the destination (To) Address
	 *
	 *	@return an instance of RWC\Endicia\Address which encapsulates the
	 *		destination (To) address for a mailpiece
	 */
	public function getDestinationAddress() : Address
	{
		return $this->destinationAddress;
	}

	/**
	 *	Sets the Mail Class to be specified on the label for a mailpiece
	 *
	 *	@param mailClass must be a string which corresponds to one of the
	 *		public constants exposed by RWC\Endicia\MailClass
	 */
	public function setLabelSize(string $size) : void
	{
		if($this->useCertifiedMail && !LabelSize::is_valid_certified_mail_label_size($size))
		{
			throw new InvalidArgumentException(
				'Label size must be one of the constants from : RWC\Endicia\LabelSize useable with Certified Mail'
			);
		}
		else if($this->useDesinationConfirmMail && !LabelSize::is_valid_destination_confirm_label_size($size))
		{
			throw new InvalidArgumentException(
				'Label size must be one of the constants from : RWC\Endicia\LabelSize usable with Desination Confirm Mail'
			);
		}
		else if(!LabelSize::is_valid($size))
		{
			throw new InvalidArgumentException(
				'Label size must be one of the constants from : RWC\Endicia\LabelSize'
			);
		}
		
		$this->labelSize = $size;
	}
	
	/**
	 *	Gets the Label Size
	 *
	 *	@return a string which corresponds to one of the public constants
	 *		exposed by RWC\Endicia\LabelSize
	 */
	public function getLabelSize() : string
	{
		if(is_null($this->labelSize)) {
			// default size
			if($this->getUseDestinationConfirmMail()) {
				// 7x3 is the default size for destination confirm mail
				return LabelSize::SIZE_7X3;
			} else {
				// 4x6 is default size otherwise
				return LabelSize::SIZE_4X6;
			}
		}
		
		return $this->labelSize;
	}

	/**
	 *	Returns the XML for the LabelRequest.
	 *
	 *	@return string Returns the LabelRequest XML.
	 */
	public function toXml() : string
	{
		//	Note <AccountID> and <PassPhrase> are not enclosed in a
		//	<CertifiedIntermediary> node, as the other requests are.
		//	Enclosing credentials in a node was added later to other
		//	methods in the Label Server API.
		$ci = $this->getCertifiedIntermediary();
		$destinationAddress = $this->getDestinationAddress();
		$returnAddress = $this->getReturnAddress();
		
		$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
//		$xml .= '<LabelRequest Test="string" LabelType="string" LabelSize="string" ImageFormat="string">';
		$xml .= '<LabelRequest';
		if($this->getUseCertifiedMailMail()) {
			$xml .= ' LabelType="CertifiedMail"';
		}
		if($this->getUseDestinationConfirmMail()) {
			$xml .= ' LabelType="DestinationConfirm"';
		}
		if($this->getLabelSize()) {
			$xml .= ' LabelSize="' . $this->getLabelSize() . '"';
		}
		$xml .= '>';
		$xml .= '<RequesterID>' . htmlentities($this->getRequesterId()) . '</RequesterID>';
		if(!empty($ci->getToken())) {	// Use Token
			$xml .= '<Token>' . htmlspecialchars($ci->getToken()) . '</Token>';
		} else {	// Use credential set.
			$xml .= '<AccountID>' . htmlspecialchars($ci->getAccountId()) . '</AccountID>';
			$xml .= '<PassPhrase>' . htmlspecialchars($ci->getPassPhrase()) . '</PassPhrase>';
		}
		$xml .= '<MailClass>' . $this->mailClass . '</MailClass>';	// no need to escape as actually enumerated value
//		$xml .= '<DateAdvance>int</DateAdvance>';
		$xml .= sprintf('<WeightOz>%.2f</WeightOz>', $this->getWeight());
//		$xml .= '<MailpieceShape>string</MailpieceShape>';
//		$xml .= '<Stealth>string</Stealth>';
//		$xml .= '<Services InsuredMail="string" SignatureConfirmation="string" />';
//		$xml .= '<Value>double</Value>';
		$xml .= '<PartnerCustomerID>UNUSED</PartnerCustomerID>';
		$xml .= '<PartnerTransactionID>' . htmlentities($this->getRequestId()) . '</PartnerTransactionID>';
		if($destinationAddress->getName()) {
			$xml .= '<ToName>' . $destinationAddress->getName() . '</ToName>';
		}
		if($destinationAddress->getCompany()) {
			$xml .= '<ToCompany>' . $destinationAddress->getCompany() . '</ToCompany>';
		}
		$xml .= '<ToAddress1>' . $destinationAddress->getAddressLine1() . '</ToAddress1>';
		if($destinationAddress->getAddressLine2()) {
			$xml .= '<ToAddress2>' . $destinationAddress->getAddressLine2() . '</ToAddress2>';
		}
		if('US' != $destinationAddress->getCountry()) {
			//	we are not to use Address Line 3 or 4 with "domestic" labels 
			if($destinationAddress->getAddressLine3()) {
				$xml .= '<ToAddress3>' . $destinationAddress->getAddressLine3() . '</ToAddress3>';
			}
			if($destinationAddress->getAddressLine4()) {
				$xml .= '<ToAddress4>' . $destinationAddress->getAddressLine4() . '</ToAddress4>';
			}
		}
		$xml .= '<ToCity>' . $destinationAddress->getCity() . '</ToCity>';
		$xml .= '<ToState>' . $destinationAddress->getState() . '</ToState>';
		$xml .= '<ToPostalCode>' . $destinationAddress->getPostalCode() . '</ToPostalCode>';
		if($destinationAddress->getDeliveryPoint()) {
			$xml .= '<ToDeliveryPoint>' . $destinationAddress->getDeliveryPoint() . '</ToDeliveryPoint>';
		}
		if($destinationAddress->getPhone()) {
			$xml .= '<ToPhone>' . $destinationAddress->getPhone() . '</ToPhone>';
		}
		if($destinationAddress->getEmail()) {
			$xml .= '<ToEMail>' . $destinationAddress->getEmail() . '</ToEMail>';
		}
		if($returnAddress->getCompany()) {
			$xml .= '<FromCompany>' . $returnAddress->getCompany() . '</FromCompany>';
		}
		if($returnAddress->getName()) {
			$xml .= '<FromName>' . $returnAddress->getName() . '</FromName>';
		}
		$xml .= '<ReturnAddress1>' . $returnAddress->getAddressLine1() . '</ReturnAddress1>';
		if($returnAddress->getAddressLine2()) {
			$xml .= '<ReturnAddress2>' . $returnAddress->getAddressLine2() . '</ReturnAddress2>';
		}
//		we are not to use Address Line 3 or 4 with "domestic" labels or internalional labels when a label subtype is supplied???
//		if('US' != $returnAddress->getCountry()) {
//			if($returnAddress->getAddressLine3()) {
//				$xml .= '<ReturnAddress3>' . $returnAddress->getAddressLine3() . '</ReturnAddress3>';
//			}
//			if($returnAddress->getAddressLine4()) {
//				$xml .= '<ReturnAddress4>' . $returnAddress->getAddressLine4() . '</ReturnAddress4>';
//			}
//		}
		$xml .= '<FromCity>' . $returnAddress->getCity() . '</FromCity>';
		$xml .= '<FromState>' . $returnAddress->getState() . '</FromState>';
		$xml .= '<FromPostalCode>' . $returnAddress->getPostalCode() . '</FromPostalCode>';
		if($returnAddress->getPhone()) {
			$xml .= '<FromPhone>' . $returnAddress->getPhone() . '</FromPhone>';
		}
		if($returnAddress->getEmail()) {
			$xml .= '<FromEMail>' . $returnAddress->getEmail() . '</FromEMail>';
		}
//		$xml .= '<ResponseOptions PostagePrice="string"/>';
		$xml .= '</LabelRequest>';
		
		return $xml;
	}
}
