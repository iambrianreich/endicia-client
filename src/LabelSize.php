<?php

/**
 * This file contains the RWC\Endicia\LabelSize enum.
 *
 * @author     Tom Egan <tom@tomegan.tech>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia;

/**
 *	A LabelSize specifies the label size.
 *
 * @author     Tom Egan <tom@tomegan.tech>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */
final class LabelSize
{
	/**
	 *	Used to select the 4" wide by 6" high label. (Default label size)
	 */
	const SIZE_4X6 = '4x6';

	/**
	 *	Used to select the 3.5" wide by 5.25" high label.
	 */
	const SIZE_3_5X5_25 = '3.5x5.25';
	
	/**
	 *	Used to select the 4" wide by 4" high label.
	 */
	const SIZE_4X4 = '4x4';
	
	/**
	 *	Used to select the 4" wide by 5" high label.
	 */
	const SIZE_4X5= '4x5';
	
	/**
	 *	Used to select the 4" wide by 4.5" high label.
	 */
	const SIZE_4X4_5 = '4x4.5';
	
	/**
	 *	Used to select the 4" wide by 6.75" high Eltron Doc-Tab label.
	 */
	const SIZE_DOC_TAB = 'DocTab';
	
	/**
	 *	Used to select the 6" wide by 4" high label.
	 */
	const SIZE_6X4 = '6x4';
	
	/**
	 *	Used to select the 4" wide by 8" high label. Shipment information
	 *		will be printed on the doctab portion of the label
	 */
	const SIZE_4X8 = '4x8';
	
	/**
	 *	Used to select the 7" wide by 3" high label. (Default for
	 *	Destination Confirm labels)
	 */
	const SIZE_7X3 = '7x3';
	
	/**
	 *	Used to select the 2.25" by 7.5" 2part internet label DYMO #30384.
	 */
	const SIZE_DYMO_30384 = 'Dymo30384';
	
	/**
	 *	Used to select the #10 envelope size.
	 */
	const SIZE_10ENVELOPE = 'EnvelopeSize10';
	
	/**
	 *	Used to select the 7" wide by 5" mailer size.
	 */
	const SIZE_7X5MAILER = 'Mailer7x5';
	
	/**
	 *	Used to select the 7" wide by 4" high label.
	 */
	const SIZE_7X4 = '7X4';
	
	/**
	 *	Used to select the 8" wide by 3" high label.
	 */
	const SIZE_8X3 = '8x3';
	
	/**
	 *	Used to select the 9" wide by 6" high envelope size.
	 */
	const SIZE_BOOKLET = 'Booklet';

	/**
	 *	An array of the public constants allows us to quickly check if a string
	 *	corresponds to a constant without reflection gymnastics
	 */
	private const ALLOWED_LABEL_SIZES = array(self::SIZE_4X6, self::SIZE_3_5X5_25, self::SIZE_4X4, self::SIZE_4X5, self::SIZE_4X4_5, self::SIZE_DOC_TAB, self::SIZE_6X4, self::SIZE_4X8);

	/**
	 *	An array of the sizes valid when destination confirmation is requested
	 */
	private const ALLOWED_DESTINATION_CONFIRM_LABEL_SIZES = array(self::SIZE_7X3, self::SIZE_6X4, self::SIZE_DYMO_30384, self::SIZE_10ENVELOPE, self::SIZE_7X5MAILER);
	
	/**
	 *	An array of the sizes valid for a certified mail label
	 */
	private const ALLOWED_CERTIFIED_MAIL_LABEL_SIZES = array(self::SIZE_4X6, self::SIZE_7X4, self::SIZE_8X3, self::SIZE_BOOKLET, self::SIZE_10ENVELOPE);

	/**
	 *	A check to determine if a given string corresponds to one of the public
	 *	constants
	 *
	 *	@param size - the string to test
	 *
	 *	@return a boolean true if and only if the string corresponds to one of
	 *		public constants
	 */
	public static function is_valid(string $size) : bool
	{
		return in_array($size, self::ALLOWED_LABEL_SIZES);
	}
	
	/**
	 *	A check to determine if a given string corresponds to one of the public
	 *	constants valid for certified mail
	 *
	 *	@param size - the string to test
	 *
	 *	@return a boolean true if and only if the string corresponds to one of
	 *		public constants
	 */
	public static function is_valid_certified_mail_label_size(string $size) : bool
	{
		return in_array($size, self::ALLOWED_CERTIFIED_MAIL_LABEL_SIZES);
	}
	
	/**
	 *	A check to determine if a given string corresponds to one of the public
	 *	constants valid if destination confirm has been requested
	 *
	 *	@param size - the string to test
	 *
	 *	@return a boolean true if and only if the string corresponds to one of
	 *		public constants
	 */
	public static function is_valid_destination_confirm_label_size(string $size) : bool
	{
		return in_array($size, self::ALLOWED_DESTINATION_CONFIRM_LABEL_SIZES);
	}
}