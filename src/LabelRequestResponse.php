<?php

/**
 * This file contains the RWC\Endicia\LabelRequestResponse class.
 *
 * @author     Brian Reich <breich@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia;

use RWC\Endicia\InvalidArgumentException;
use RWC\Endicia\AbstractResponse;

/**
 *	A LabelRequestResponse is a response from the GetPostageLabel API service.
 *
 *	The response specifies whether or not the request was successful through the
 *	Status and ErrorMessage fields. If the request was successful, the label will
 *	be available via getLabel.
 *
 * @author     Tom Egan <tom@tomegan.tech>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */
class LabelRequestResponse extends AbstractResponse
{
    /**
     * The label as a list of base 64 encoded image fragments.
     *
     * @var array
     */
	private $imageFragments;
	
    /**
     * The label as base 64 encoded image data.
     *
     * @var string
     */
	private $encodedImageData;
	
    /**
     * The tracking number.
     *
     * @var string
     */
	private $trackingNumber;
	
	public function getBase64EncodedLabel() : string
	{
		if(empty($this->encodedImageData)) {
			$this->encodedImageData = join('', $this->imageFragments);
		}

		return $this->encodedImageData;
	}
	
	/**
	 *	Get the label data
	 *
	 *	@return string - the label data as a string. Note this may represent
	 *		one type of a wide variety of data types from JPEG image data to
	 *		text based printer instruction (ZPLII)
	 */
	public function getLabel() : string
	{
		return base64_decode($this->getBase64EncodedLabel());
	}
	
	private function setTrackingNumber(string $tracking)
	{
		$this->trackingNumber = $tracking;
	}
	
	/**
	 *	Get the tracking number
	 *
	 *	@return string - the tracking number. Note the length of this string is
	 *		determined by a number of factors including whether the postage is
	 *		for domestic delivery or international and the Mail Class.
	 */
	public function getTrackingNumber() : string
	{
		return $this->trackingNumber;
	}
	
	/**
	 *	Construct a LabelRequestResponse Object from xml data
	 *
	 *	@param string xml - the xml data to parse and use to populate the
	 *		object properties
	 *	@param AbstractResponse|null - The response object to copy data into.
	 *		Most implementations should omit this parameter or pass in null so
	 *		that a new response object is created.
	 *
	 *	@return LabelRequestResponse - A object representing the data encoded
	 *		in the supplied XML.
	 */
    public static function fromXml(string $xml, AbstractResponse $response = null) : AbstractResponse
    {
        // Force an object.
        $response = $response ?? new LabelRequestResponse();

        try {
            parent::fromXml($xml, $response);

            $xml = str_replace('www.envmgr.com/LabelService', '', $xml);
            
            $dom = new \DOMDocument();
            $dom->loadXML($xml);

            $xp = new \DOMXPath($dom);

            // If it's a failure we're done.
            if(!$response->isSuccessful()) {
                return $response;
            }

            /*
             * Validate/Set Label Image data
             */
            $b64ImageNodeList = $xp->query('Base64LabelImage');
			if($b64ImageNodeList->length == 1) {
            	// we found the image as a single run of data
				$response->encodedImageData = $b64ImageNodeList[0]->nodeValue;
			} else {
				// a successful label but the label is not in Base64LabelImage
				// that means the Label node must exist and contain one or more chunks
				$labelNodeList = $xp->query('Label');
				if($labelNodeList->length != 1) {
	                throw new InvalidArgumentException('API indicated a successful response but returned no label data');
				}
				
				$response->imageFragments = array();
				$imageFragments = $labelNodeList[0]->childNodes;
				foreach($imageFragments as $fragment) {
					$index = $fragment->getAttribute('PartNumber');
					$response->imageFragments[$index] = $fragment->textContent;
				}
			}
			
            /*
             * Validate/Set Tracking Number
             */
            $trackingNumberNodeList = $xp->query('TrackingNumber');
            if($trackingNumberNodeList->length == 0) {
                throw new InvaliArgumentException('Response did not contain a Tracking Number');
            }

            $response->setTrackingNumber($trackingNumberNodeList[0]->textContent);
			
            return $response;
        } catch(\Exception $e) {
            throw new InvalidArgumentException('Invalid LabelRequestResponse XML. ' . $e->getMessage(), null, $e);
        }
    }

    protected function __construct()
    {
    }
}
