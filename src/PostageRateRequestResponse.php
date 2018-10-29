<?php

/**
 * This file contains the RWC\Endicia\PostageRateRequestResponseTest class.
 *
 * @author     Joshua Stroup <jstroup@stroupcreative.group>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia;

class PostageRateRequestResponse extends AbstractResponse
{
    /**
     * @var int
     */
    protected $zone;

    /**
     * @var array
     */
    protected $postage;

    /**
     * @var array
     */
    protected $postagePrice;

    /**
     * Returns the number of postal zones to destination
     *
     * @return int Returns the number of postal zones to destination
     */
    public function getZone() : int
    {
        return $this->zone;
    }

    /**
     * Sets the number of postal zones to destination
     *
     * @param int $zone The number of postal zones to destination
     */
    public function setZone(int $zone) : void
    {
        $this->zone = $zone;
    }

    /**
     * Returns the Postage array from the response
     *
     * @return array|null Returns the Postage array from the response
     */
    public function getPostage() : ?array
    {
        return $this->postage;
    }

    /**
     * Sets the Postage array from the response
     *
     * @param array|null $postage The Postage Node from the response as an array
     */
    public function setPostage(?array $postage) : void
    {
        $this->postage = $postage;
    }

    /**
     * Returns the PostagePrice Node as an array
     *
     * @return array|null Returns the PostagePrice Node as an array
     */
    public function getPostagePrice() : ?array
    {
        return $this->postagePrice;
    }

    /**
     * Sets the PostagePrice array from the response
     *
     * @param array|null $postagePrice An array of the PostagePrice Node
     */
    public function setPostagePrice(?array $postagePrice) : void
    {
        $this->postagePrice = $postagePrice;
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
        $response = $response ?? new PostageRateRequestResponse();

        try {
            parent::fromXml($xml, $response);

            $xml = str_replace('www.envmgr.com/LabelService', '', $xml);
            $dom = new \DOMDocument();
            $dom->loadXML($xml);

            if (!$response->isSuccessful()) {
                return $response;
            }

            // Set Zone if it exists
            $zone = $dom->getElementsByTagName('Zone');
            if ($zone->length > 0) {
                $response->setZone((int) $zone[0]->nodeValue);
            }

            // Parse PostagePrice Node if it exists
            $postagePrice = $dom->getElementsByTagName('PostagePrice');
            if ($postagePrice->length > 0) {
                $response->setPostagePrice([]);

                $response->postagePrice['totalAmount'] = (float) $postagePrice[0]->attributes[0]->value;

                // Parse the Postage Node if it exists
                $xpath = new \DOMXPath($dom);
                $query = '//PostagePrice/Postage';
                $postage = $xpath->query($query);
                if ($postage->length > 0) {
                    $response->postagePrice['postage'] = [];

                    $response->postagePrice['postage']['totalAmount'] = (float) $postage[0]->attributes[0]->value;

                    $mailService = $dom->getElementsByTagName('MailService');
                    if ($mailService->length > 0) {
                        $response->postagePrice['postage']['mailService'] = $mailService[0]->nodeValue;
                    }

                    // Not sure why there's another Zone element, but if there is, parse it.
                    if ($zone->length > 1) {
                        $response->postagePrice['postage']['zone'] = (int) $zone[1]->nodeValue;
                    }

                    $intraNDC = $dom->getElementsByTagName('IntraNDC');
                    if ($intraNDC->length > 0) {
                        $response->postagePrice['postage']['intraNDC'] = (bool) $intraNDC[0]->nodeValue;
                    }

                    $pricing = $dom->getElementsByTagName('Pricing');
                    if ($pricing->length > 0) {
                        $response->postagePrice['postage']['pricing'] = $pricing[0]->nodeValue;
                    }
                }

                // Parse Fees Node if it exists
                $fees = $dom->getElementsByTagName('Fees');
                if ($fees->length > 0) {
                    $response->postagePrice['fees'] = [];

                    $certOfMailing = $dom->getElementsByTagName('CertificateOfMailing');
                    if ($certOfMailing->length > 0) {
                        $response->postagePrice['fees']['certificateOfMailing'] = (float) $certOfMailing[0]->nodeValue;
                    }

                    $certifiedMail = $dom->getElementsByTagName('CertifiedMail');
                    if ($certifiedMail->length > 0) {
                        $response->postagePrice['fees']['certifiedMail'] = (float) $certifiedMail[0]->nodeValue;
                    }

                    $collectOnDelivery = $dom->getElementsByTagName('CollectOnDelivery');
                    if ($collectOnDelivery->length > 0) {
                        $response->postagePrice['fees']['collectOnDelivery'] = (float) $collectOnDelivery[0]->nodeValue;
                    }

                    $deliveryConfirmation = $dom->getElementsByTagName('DeliveryConfirmation');
                    if ($deliveryConfirmation->length > 0) {
                        $response->postagePrice['fees']['deliveryConfirmation'] = (float) $deliveryConfirmation[0]->nodeValue;
                    }

                    $electronicReturnReceipt = $dom->getElementsByTagName('ElectronicReturnReceipt');
                    if ($electronicReturnReceipt->length > 0) {
                        $response->postagePrice['fees']['electronicReturnReceipt'] = (float) $electronicReturnReceipt[0]->nodeValue;
                    }

                    $insuredMail = $dom->getElementsByTagName('InsuredMail');
                    if ($insuredMail->length > 0) {
                        $response->postagePrice['fees']['insuredMail'] = (float) $insuredMail[0]->nodeValue;
                    }

                    $registeredMail = $dom->getElementsByTagName('RegisteredMail');
                    if ($registeredMail->length > 0) {
                        $response->postagePrice['fees']['registeredMail'] = (float) $registeredMail[0]->nodeValue;
                    }

                    $restrictedDelivery = $dom->getElementsByTagName('RestrictedDelivery');
                    if ($restrictedDelivery->length > 0) {
                        $response->postagePrice['fees']['restrictedDelivery'] = (float) $restrictedDelivery[0]->nodeValue;
                    }

                    $returnReceipt = $dom->getElementsByTagName('ReturnReceipt');
                    if ($returnReceipt->length > 0) {
                        $response->postagePrice['fees']['returnReceipt'] = (float) $returnReceipt[0]->nodeValue;
                    }

                    $returnReceiptForMerchandise = $dom->getElementsByTagName('ReturnReceiptForMerchandise');
                    if ($returnReceiptForMerchandise->length > 0) {
                        $response->postagePrice['fees']['returnReceiptForMerchandise'] = (float) $returnReceiptForMerchandise[0]->nodeValue;
                    }

                    $signatureConfirmation = $dom->getElementsByTagName('SignatureConfirmation');
                    if ($signatureConfirmation->length > 0) {
                        $response->postagePrice['fees']['signatureConfirmation'] = (float) $signatureConfirmation[0]->nodeValue;
                    }

                    $specialHandling = $dom->getElementsByTagName('SpecialHandling');
                    if ($specialHandling->length > 0) {
                        $response->postagePrice['fees']['specialHandling'] = (float) $specialHandling[0]->nodeValue;
                    }

                    $adultSignature = $dom->getElementsByTagName('AdultSignature');
                    if ($adultSignature->length > 0) {
                        $response->postagePrice['fees']['adultSignature'] = (float) $adultSignature[0]->nodeValue;
                    }

                    $adultSignatureRestrictedDelivery = $dom->getElementsByTagName('AdultSignatureRestrictedDelivery');
                    if ($adultSignatureRestrictedDelivery->length > 0) {
                        $response->postagePrice['fees']['adultSignatureRestrictedDelivery'] = (float) $adultSignatureRestrictedDelivery[0]->nodeValue;
                    }

                    $liveAnimalSurcharge = $dom->getElementsByTagName('LiveAnimalSurcharge');
                    if ($liveAnimalSurcharge->length > 0) {
                        $response->postagePrice['fees']['liveAnimalSurcharge'] = (float) $liveAnimalSurcharge[0]->nodeValue;
                    }

                    $deliveryTimeDays = $dom->getElementsByTagName('DeliveryTimeDays');
                    if ($deliveryTimeDays->length > 0) {
                        $response->postagePrice['fees']['deliveryTimeDays'] = $deliveryTimeDays[0]->nodeValue;
                    }

                    $estimatedDeliveryDate = $dom->getElementsByTagName('EstimatedDeliveryDate');
                    if ($estimatedDeliveryDate->length > 0) {
                        $response->postagePrice['fees']['estimatedDeliveryDate'] = $estimatedDeliveryDate[0]->nodeValue;
                    }

                    $amDelivery = $dom->getElementsByTagName('AMDelivery');
                    if ($amDelivery->length > 0) {
                        $response->postagePrice['fees']['amDelivery'] = (float) $amDelivery[0]->nodeValue;
                    }

                    // Parse GroupedExtraServices Node if it exists
                    $groupedExtraServices = $dom->getElementsByTagName('GroupedExtraServices');
                    if ($groupedExtraServices->length > 0) {
                        $response->postagePrice['fees']['groupedExtraServices'] = [];

                        // Parse Services Attribute if it exists
                        if ($groupedExtraServices[0]->attributes[0] != null) {
                            $response->postagePrice['fees']['groupedExtraServices']['services'] = $groupedExtraServices[0]->attributes[0]->value;
                        }

                        $feeAmount = $dom->getElementsByTagName('FeeAmount');
                        if ($feeAmount->length > 0) {
                            $response->postagePrice['fees']['groupedExtraServices']['feeAmount'] = (float) $feeAmount[0]->nodeValue;
                        }
                    }
                }
            } else {
                // Otherwise, just parse the Postage Node
                $response->setPostage([
                    'mailService' => $dom->getElementsByTagName('MailService')[0]->nodeValue,
                    'rate' => $dom->getElementsByTagName('Rate')[0]->nodeValue
                ]);
            }

            return $response;
        } catch (\Exception $e) {
            throw new InvalidArgumentException("Invalid PostageRateRequestResponse XML. " . $e->getMessage(), null, $e);
        }
    }

    protected function __construct()
    {
    }
}