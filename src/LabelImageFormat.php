<?php

/**
 * This file contains the RWC\Endicia\LabelImageFormat enum.
 *
 * @author     Tom Egan <tom@tomegan.tech>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia;

/**
 *	A LabelImageFormat specifies the label image format.
 *
 * @author     Tom Egan <tom@tomegan.tech>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */
final class LabelImageFormat
{
	/**
	 *	Used to select the EPL2, a text based printer language, format
	 */
	const EPL2 = 'EPL2';
	
	/**
	 *	Used to select the ZPLII, a text based printer language, format
	 */
	const ZPLII = 'ZPLII';
	
	/**
	 *	Used to select the GIF, a bitmap image, format
	 */
	const GIF = 'GIF';
	
	/**
	 *	Used to select the GIF, a bitmap image, format
	 */
	const GIFMONOCHROME = 'GIFMONOCHROME';
	
	/**
	 *	Used to select the GIF, a bitmap image, format
	 */
	const JPEG = 'JPEG';
	
	/**
	 *	Used to select the GIF, a bitmap image, format
	 */
	const JPEGMONOCHROME = 'JPEGMONOCHROME';
	
	/**
	 *	Used to select the GIF, a bitmap image, format
	 */
	const PNG = 'PNG';

	/**
	 *	Used to select the GIF, a bitmap image, format
	 */
	const PNGMONOCHROME = 'PNGMONOCHROME';

	/**
	 *	Used to select the GIF, a bitmap image, format
	 */
	const BMPMONOCHROME = 'BMPMONOCHROME';

	/**
	 *	Used to select the PDF, a text based hypothetical-printer language,
	 *		format
	 */
	const PDF = 'PDF';

	/**
	 *	Used to select the PDF/Vector, a vector image wrapped in a PDF
	 *		document, format
	 */
	const PDFVector = 'PDFVector';

	/**
	 *	Used to select the PDF/Vector w/ Embedded Fonts, vector image
	 *		wrapped in a PDF document with embedded font data, format
	 */
	const PDFVectorWithFonts = 'PDFVectorWithFonts';

	/**
	 *	An array of the public constants allows us to quickly check if a string
	 *	corresponds to a constant without reflection gymnastics
	 */
	private const ALLOWED_IMAGE_FORMATS = array(self::EPL2, self::ZPLII, self::GIF, self::GIFMONOCHROME, self::JPEG, self::JPEGMONOCHROME, self::PNG, self::PNGMONOCHROME, self::BMPMONOCHROME, self::PDF, self::PDFVector, self::PDFVectorWithFonts);

	/**
	 *	A check to determine if a given string corresponds to one of the public
	 *	constants
	 *
	 *	@param string format - the string to test
	 *
	 *	@return boolean true if and only if the string corresponds to one of
	 *		public constants
	 */
	public static function is_valid(string $format) : bool
	{
		return in_array($format, self::ALLOWED_IMAGE_FORMATS);
	}
}