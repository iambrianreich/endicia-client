<?php

/**
 * This file contains the RWC\Endicia\MailClass enum.
 *
 * @author     Tom Egan <tom@tomegan.tech>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia;

/**
 *	A MailClass specifies the handling priority of a mailpiece.
 *
 * @author     Tom Egan <tom@tomegan.tech>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */
final class MailClass
{
	/**
	 *	Used to select the "Priority Mail Express" mail class.
	 */
	const PRIORITYEXPRESS = 'PriorityExpress';

	/**
	 *	Used to select the "First-Class Package Service" and
	 *	"First-Class Mail Parcel" mail classes.
	 */
	const FIRST = 'First';

	/**
	 *	Used to select the "Library Mail" mail class.
	 */
	const LIBRARYMAIL = 'LibraryMail';

	/**
	 *	Used to select the "Media Mail" mail class.
	 */
	const MEDIAMAIL = 'MediaMail';

	/**
	 *	Used to select the "Priority Mail" mail class.
	 */
	const PRIORITY = 'Priority';
	
	/**
	 *	Used to select the "Parcel Select" mail class. Available to most but
	 *	not all users. Note that SortType and EntryFacility must be set for
	 *	LabelRequests using this mail class to be valid
	 */
	const PARCELSELECT = 'ParcelSelect';
	
	/**
	 *	Used to select the "Retail Ground" mail class. Note: Retail Ground
	 *	is available only for use by USPS Authorized Shippers.
	 */
	const RETAILGROUND = 'RetailGround';

    /**
     * Used to select the "Priority Mail Express International" mail class.
     */
    const PRIORITYMAILEXPRESSINTERNATIONAL = 'PriorityMailExpressInternational';

    /**
     * Used to select the "First Class Mail International" mail class.
     */
    const FIRSTCLASSMAILINTERNATIONAL = 'FirstClassMailInternational';

    /**
     * Used to select the "First Class Package International" mail class.
     */
    const FIRSTCLASSPACKAGEINTERNATIONAL = 'FirstClassPackageInternational';

    /**
     * Used to select the "Priority Mail International" mail class.
     */
    const PRIORITYMAILINTERNATIONAL = 'PriorityMailInternational';

    /**
     * Used for PostageRatesRequest to designate a domestic shipment
     */
    const DOMESTIC = 'Domestic';

    /**
     * Used for PostageRatesRequest to designate an international shipment
     */
    const INTERNATIONAL = 'International';

	/**
	 *	An array of the public constants allows us to quickly check if a string
	 *	corresponds to a constant without reflection gymnastics
	 */
	private const ALLOWED_MAIL_CLASSES = array(self::PRIORITYEXPRESS, self::FIRST, self::LIBRARYMAIL, self::MEDIAMAIL, self::PRIORITY, self::PARCELSELECT, self::RETAILGROUND, self::PRIORITYMAILEXPRESSINTERNATIONAL, self::FIRSTCLASSMAILINTERNATIONAL, self::FIRSTCLASSPACKAGEINTERNATIONAL, self::PRIORITYMAILINTERNATIONAL, self::DOMESTIC, self::INTERNATIONAL);
	
	/**
	 *	A check to determine if a given string corresponds to one of the public
	 *	constants
	 *
	 *	@param string mailClass - the string to test
	 *
	 *	@return boolean true if and only if the string corresponds to one of
	 *		public constants
	 */
	public static function is_valid(?string $mailClass) : bool
	{
		return in_array($mailClass, self::ALLOWED_MAIL_CLASSES);
	}
}