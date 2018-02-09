<?php

/**
 * This file contains the RWC\Endicia\GetLabelRequest class.
 *
 * @author     Tom Egan <tom@tomegan.tech>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia;

use RWC\Endicia\AbstractRequest;
use RWC\Endicia\Address;
use RWC\Endicia\InvalidArgumentException;

/**
 *	A LabelRequest fetches a printable postage label.
 *
 *	The LabelRequest requires the basic request parameters required by all
 *		Endicia requests: a requester id and a CertifiedIntermediary
 *		(authentication credentials) which will actually be charged for the
 *		activity.
 *
 *	The LabelRequest also requires the label size, the image format, the
 *		mail class, the mailpiece weight (in ounces), the mailpiece shape, the
 *		pickup/origin postal code, the From/Return Address and the To/Destination
 *		Address
 *
 * @author     Brian Reich <tom@tomegan.tech>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */
class LabelRequest extends AbstractRequest
{
	/**
	 *	Used to select the "Priority Mail Express" mail class.
	 */
	const MAIL_CLASS_PRIORITYEXPRESS = 'PriorityExpress';

	/**
	 *	Used to select the "First-Class Package Service" and
	 *	"First-Class Mail Parcel" mail classes.
	 */
	const MAIL_CLASS_FIRST = 'First';

	/**
	 *	Used to select the "Library Mail" mail class.
	 */
	const MAIL_CLASS_LIBRARYMAIL = 'LibraryMail';

	/**
	 *	Used to select the "Media Mail" mail class.
	 */
	const MAIL_CLASS_MEDIAMAIL = 'MediaMail';

	/**
	 *	Used to select the "Priority Mail" mail class.
	 */
	const MAIL_CLASS_PRIORITY = 'Priority';
	
	/**
	 *	Used to selecte the "Parcel Select" mail class. Available to most but
	 *	not all users. Note that SortType and EntryFacility must be set for
	 *	LabelRequests using this mail class to be valid
	 */
	const MAIL_CLASS_PARCELSELECT = 'ParcelSelect';
	
	/**
	 *	Used to select the "Retail Ground" mail class. Note: Retail Ground
	 *	is available only for use by USPS Authorized Shippers.
	 */
	const MAIL_CLASS_RETAILGROUND = 'RetailGround';

	private const ALLOWED_MAIL_CLASSES = array(self::MAIL_CLASS_PRIORITYEXPRESS, self::MAIL_CLASS_FIRST, self::MAIL_CLASS_LIBRARYMAIL, self::MAIL_CLASS_MEDIAMAIL, self::MAIL_CLASS_PRIORITY, self::MAIL_CLASS_PARCELSELECT, self::MAIL_CLASS_RETAILGROUND);

	/**
	 *	The mail class for the mailpiece the requested label will be affixed to
	 *	Must be one of the MAIL_CLASS_* constants
	 *
	 *	@var String
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
	
//	private $labelSize;
//	private $imageFormat;
//	private $shape;
//	private $originPostalCode;

	/**
	 * Creates a new LabelRequest.
	 *
	 *	The request must specify the requester id which identifies the
	 *	application making the request. The CertifiedIntermediary is the
	 *	authentication token that provides either the account id and password, or
	 *	the security token for the account. The label size is one of the
	 *	enumerated values for supported label size
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
		$this->setMailClass($mailClass);
		$this->setWeight($weight);
		$this->setReturnAddress($returnAddress);
		$this->setDestinationAddress($destinationAddress);
	}

	/**
	 *
	 */
	public function setMailClass(string $mailClass) : void
	{
		if(!in_array($mailClass, self::ALLOWED_MAIL_CLASSES))
		{
			throw new InvalidArgumentException(
				'Mail Class must be one of: ' . join(', ', self::ALLOWED_MAIL_CLASSES)
			);
		}
		
		$this->mailClass = $mailClass;
	}
	
	/**
	 *
	 */
	public function getMailClass() : string
	{
		return $this->mailClass;
	}
	
	/**
	 *
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
	 *
	 */
	public function getWeight() : float
	{
		return $this->weight;
	}
	
	/**
	 *
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
	 *
	 */
	public function getReturnAddress() : Address
	{
		return $this->returnAddress;
	}
	
	/**
	 *
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
	 *
	 */
	public function getDestinationAddress() : Address
	{
		return $this->destinationAddress;
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
		$xml .= '<LabelRequest>';
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
		if($returnAddress->getName()) {
			$xml .= '<FromName>' . $returnAddress->getName() . '</FromName>';
		}
		if($returnAddress->getCompany()) {
			$xml .= '<FromCompany>' . $returnAddress->getCompany() . '</FromCompany>';
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
