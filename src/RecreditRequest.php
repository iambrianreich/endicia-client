<?php

/**
 * This file contains the RWC\Endicia\RecreditRequest class.
 *
 * @author     Brian Reich <breich@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia;

use RWC\Endicia\AbstractRequest;
use RWC\Endicia\InvalidArgumentException;

/**
 * A RecreditRequest adds funds to a postage account.
 *
 * The RecreditRequest requires the basic request parameters required by all
 * Endicia requests: a requester id and a CertifiedIntermediary (authentication
 * credentials) which will actually be charged for the activity.
 *
 * The RecreditRequest also requires the recredit amount, which specifies the
 * amount, in U.S. Dollars, to add to the postage account. The recredit amount
 * must be $10.00 or more and less than $100,000 (>= $99,999.99). If this
 * criteria is not met an InvalidArgumentException will be thrown.
 *
 * @author     Brian Reich <breich@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */
class RecreditRequest extends AbstractRequest
{
    /**
     * The amount of credit to add to the account.
     *
     * @var float
     */
    private $recreditAmount;

    /**
     * Creates a new RecreditRequest.
     *
     * The request must specify the requester id which identifies the
     * application making the request. The CertifiedIntermediary is the
     * authentication token that provides either the account id and password, or
     * the security token for the account. The final parameter specifies the
     * credit amount to add to the account. The credit amount must be great then
     * or equal to 10.00 and less than or equal to 99,999.99.
     *
     * If these validation requirements are not met, an InvalidArgumentException
     * is thrown.
     *
     * @param string                $requesterId           The id of the requester.
     * @param CertifiedIntermediary $certifiedIntermediary The authentication token.
     * @param float                 $recreditAmount        The credit amount.
     *
     * @throws InvalidArgumentException if the recredit amount is invalid.
     */
    public function __construct(
        string $requesterId,
        CertifiedIntermediary $certifiedIntermediary,
        float $recreditAmount
    ) {
        parent::__construct($requesterId, $certifiedIntermediary);
        $this->setRecreditAmount($recreditAmount);
    }

    /**
     * Sets the recredit amount.
     *
     * The minimum amount of credit that can be purchased in a single request is
     * 10.00. The maximum amount that can be purchased in a single request is
     * $99,999.99. If the amount passed does not meet this criteria an
     * InvalidArgumentException is thrown.
     *
     * @param float $recreditAmount The recredit amount.
     *
     * @throws InvalidArgumentException if the recredit amount is invalid.
     */
    public function setRecreditAmount(float $recreditAmount) : void
    {
        if ($recreditAmount < 10.00) {
            throw new InvalidArgumentException("The minimum amount of credit " .
                "that can be purchased is 10.00.");
        }

        if ($recreditAmount > 99999.99) {
            throw new InvalidArgumentException("The maximum amount of credit " .
            "that can be purchased is 99,999.99");
        }

        $this->recreditAmount = $recreditAmount;
    }

    /**
     * Returns the recredit amount.
     *
     * @return float Returns the recredit amount.
     */
    public function getRecreditAmount() : float
    {
        return $this->recreditAmount;
    }

    /**
     * Returns the XML for the RecreditRequest.
     *
     * @return string Returns the RecreditRequest XML.
     */
    public function toXml() : string
    {
        return "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n" .
               '<RecreditRequest>' .
               parent::toXml() .
               sprintf(
                   '<RecreditAmount>%.2f</RecreditAmount>',
                   $this->getRecreditAmount()
               ) .
               '</RecreditRequest>';
    }
}
