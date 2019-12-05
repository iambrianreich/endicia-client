<?php

/**
 * This file contains the RWC\Endicia\PostageRatesRequestResponse class.
 *
 * @author     Joshua Stroup <josh.stroup@reich-consulting.net>
 * @copyright  (C) Copyright 2019 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia;

class PostageRatesRequestResponse extends AbstractResponse
{
    /**
     * @var array
     */
    protected $postagePrices;

    /**
     * Returns the PostagePrice Nodes as an array
     *
     * @return array|null Returns the PostagePrice Nodes as an array
     */
    public function getPostagePrices() : ?array
    {
        return $this->postagePrices;
    }

    /**
     * Sets the PostagePrice array from the response
     *
     * @param array|null $postagePrices An array of the PostagePrice Node
     */
    public function setPostagePrices(?array $postagePrices) : void
    {
        $this->postagePrices = $postagePrices;
    }

    /**
     * Returns the PostageRateRequestResponse object after parsing the XML
     * Each Node that contains child elements is in its own array named after the Node
     * If you did not specify PostagePrice in the ResponseOptions Node, it will be null.
     * Otherwise, the $postage variable will be null and all responses will be in $postagePrice
     *
     * @param string $xml The XML from the response
     * @param AbstractResponse|null $response
     *
     * @return AbstractResponse Returns the Response object with the XML parsed
     *
     * @throws InvalidArgumentException
     */
    public static function fromXml(string $xml, AbstractResponse $response = null) : AbstractResponse
    {
        // Force an object.
        $response = $response ?? new PostageRatesRequestResponse();

        try {
            parent::fromXml($xml, $response);

            $xml = str_replace('www.envmgr.com/LabelService', '', $xml);
            $dom = new \DOMDocument();
            $dom->loadXML($xml);

            if (!$response->isSuccessful()) {
                return $response;
            }

            $response->setPostagePrices([]);

            $i = 0;
            while (is_object($postagePrice = $dom->getElementsByTagName('PostagePrice')->item($i))) {
                $response->postagePrices[$i]['totalAmount'] = (float) $postagePrice->attributes[0]->value;

                foreach ($postagePrice->childNodes as $node) {
                    if ($node->nodeName == 'Postage') {
                        $response->postagePrices[$i]['postage']['totalAmount'] = (float) $node->attributes[0]->value;

                        foreach ($node->childNodes as $childNode) {
                            $response->postagePrices[$i]['postage'][lcfirst($childNode->nodeName)] = $childNode->nodeValue;
                        }
                    } else if ($node->nodeName == 'Fees') {
                        foreach ($node->childNodes as $childNode) {
                            if ($childNode->nodeName == 'GroupedExtraServices') {
                                $response->postagePrices[$i]['fees']['groupedExtraServices']['services'] = $childNode->attributes[0]->value;
                                $response->postagePrices[$i]['fees']['groupedExtraServices']['feeAmount'] = $childNode->childNodes->item(0)->nodeValue;
                            } else if ($childNode->nodeName == 'AMDelivery') {
                                $response->postagePrices[$i]['fees']['amDelivery'] = $childNode->nodeValue;
                            } else {
                                $response->postagePrices[$i]['fees'][lcfirst($childNode->nodeName)] = $childNode->nodeValue;
                            }
                        }
                    } else {
                        $response->postagePrices[$i][lcfirst($node->nodeName)] = $node->nodeValue;
                    }
                }

                $i++;
            }

            return $response;
        } catch (\Exception $e) {
            throw new InvalidArgumentException("Invalid PostageRatesRequestResponse XML. " . $e->getMessage(), null, $e);
        }
    }

    protected function __construct()
    {
    }
}