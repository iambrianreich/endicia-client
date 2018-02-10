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
	 *	Used to selecte the "Parcel Select" mail class. Available to most but
	 *	not all users. Note that SortType and EntryFacility must be set for
	 *	LabelRequests using this mail class to be valid
	 */
	const PARCELSELECT = 'ParcelSelect';
	
	/**
	 *	Used to select the "Retail Ground" mail class. Note: Retail Ground
	 *	is available only for use by USPS Authorized Shippers.
	 */
	const RETAILGROUND = 'RetailGround';
	
	private const ALLOWED_MAIL_CLASSES = array(self::PRIORITYEXPRESS, self::FIRST, self::LIBRARYMAIL, self::MEDIAMAIL, self::PRIORITY, self::PARCELSELECT, self::RETAILGROUND);
	
	public static function is_valid(string $mailClass) : bool
	{
		return in_array($mailClass, self::ALLOWED_MAIL_CLASSES);
	}
}