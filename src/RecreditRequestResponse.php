<?php

/**
 * This file contains the RWC\Endicia\RecreditRequestResponse class.
 *
 * @author     Brian Reich <breich@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia;

use RWC\Endicia\InvalidArgumentException;
use RWC\Endicia\AbstractResponse;
use RWC\Endicia\RecreditRequestResponse\CertifiedIntermediary;

/**
 * A RecreditRequestResponse is a response from te RecreditRequest API service.
 *
 * The response specifies whether or not the request was successful through the
 * Status and ErrorMessage fields. If the request was successful, the account's
 * status will be provided in the CertifiedIntermediary.
 *
 * @author     Brian Reich <breich@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */
class RecreditRequestResponse extends AbstractResponse
{
    /**
     * The CertifiedIntermediary contains the account status.
     *
     * @var CertifiedIntermediary
     */
    private $certifiedIntemediary;

    /**
     * Returns the CertifiedIntermediary.
     *
     * @param CertifiedIntermediary $certifiedIntermediary The account status.
     */
    public function setCertifiedIntermediary(CertifiedIntermediary $certifiedIntermediary) : void
    {
        $this->certifiedIntemediary = $certifiedIntermediary;
    }

    /**
     * Returns the CertifiedIntermediary containing the account status.
     *
     * @return CertifiedIntermediary Returns the account status.
     */
    public function getCertifiedIntermediary() : CertifiedIntermediary
    {
        return $this->certifiedIntemediary;
    }

    public static function fromXml(string $xml, AbstractResponse $response = null) : AbstractResponse
    {
        // Force an object.
        $response = $response ?? new RecreditRequestResponse();

        try {
            parent::fromXml($xml, $response);

            $xml = str_replace('www.envmgr.com/LabelService', '', $xml);
            
            $dom = new \DOMDocument();
            $dom->loadXML($xml);

            $xp    = new \DOMXPath($dom);

            // If it's a failure we're done.
            if (! $response->isSuccessful()) {
                return $response;
            }

            // Make sure CertifiedIntermediary exists.
            $ciNodes = $dom->getElementsByTagName("CertifiedIntermediary");

            if ($ciNodes->length == 0) {
                throw new InvalidArgumentException("RecreditRequestResponse " .
                    "does not contain CertifiedIntermediary.");
            }

            $ci = new CertifiedIntermediary();

            /*
             * Validate/Set AccountID
             */
            $accountId = $xp->query('CertifiedIntermediary/AccountID');

            if ($accountId->length == 0) {
                throw new InvaliArgumentException('CertifiedIntermediary did ' .
                    'not contain an AccountID');
            }

            $ci->setAccountId((string) $accountId[0]->nodeValue);

            /*
             * Validate/Set SerialNumber
             */
            $serialNo = $xp->query('CertifiedIntermediary/SerialNumber');

            if ($serialNo->length == 0) {
                throw new InvaliArgumentException('CertifiedIntermediary did ' .
                    'not contain an SerialNumber');
            }

            $ci->setSerialNumber((string) $serialNo[0]->nodeValue);
            
            /*
             * Validate/Set PostageBalance
             */
            $postageBalance = $xp->query('CertifiedIntermediary/PostageBalance');

            if ($postageBalance->length == 0) {
                throw new InvaliArgumentException('CertifiedIntermediary did ' .
                    'not contain an PostageBalance');
            }

            $ci->setPostageBalance((float) $postageBalance[0]->nodeValue);

            /*
             * Validate/Set AscendingBalance
             */
            $ascendingBalance = $xp->query('CertifiedIntermediary/AscendingBalance');

            if ($ascendingBalance->length == 0) {
                throw new InvaliArgumentException('CertifiedIntermediary did ' .
                    'not contain an AscendingBalance');
            }

            $ci->setAscendingBalance((float) $ascendingBalance[0]->nodeValue);


            /*
             * Validate/Set AccountStatus
             */
            $accountStatus = $xp->query('CertifiedIntermediary/AccountStatus');

            if ($accountStatus->length == 0) {
                throw new InvaliArgumentException('CertifiedIntermediary did ' .
                    'not contain an AccountStatus');
            }

            $ci->setAccountStatus((string) $accountStatus[0]->nodeValue);

            /*
             * Validate/Set DeviceID
             */
            $deviceID = $xp->query('CertifiedIntermediary/DeviceID');

            if ($deviceID->length == 0) {
                throw new InvaliArgumentException('CertifiedIntermediary did ' .
                    'not contain an DeviceID');
            }

            $ci->setDeviceID((string) $deviceID[0]->nodeValue);
            
            $response->setCertifiedIntermediary($ci);
            
            return $response;
        } catch (\Exception $e) {
            throw new InvalidArgumentException(
                "Invalid RecreditRequestResponse XML. " .
                $e->getMessage(),
                null,
                $e
            );
        }
    }

    protected function __construct()
    {
    }
}
