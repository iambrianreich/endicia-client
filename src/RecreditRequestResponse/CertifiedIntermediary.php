<?php

/**
 * This file contains the RWC\Endicia\RecreditRequestResponse\CertifiedIntermediary class.
 *
 * @author     Brian Reich <breich@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia\RecreditRequestResponse;

use RWC\Endicia\InvalidArgumentException;

/**
 * Specifies account details returned by RecreditRequest.
 *
 * When RecreditRequest is called successfully it will add funds to the postage
 * account. The response will contain a special type of CertifiedIntermediary
 * that contains the account id and details about the state of the account, how
 * much money is in the account, and how much has been previously spent.
 *
 * @author     Brian Reich <breich@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */
class CertifiedIntermediary
{
    /**
     * Account ID for the Endicia postage account.
     *
     * @var string
     */
    private $accountId;

    /**
     * The Serial Number of the last item created on the account.
     *
     * @var string
     */
    private $serialNumber;

    /**
     * Remaining postage balance after re-credit in dollars and cents (mils).
     *
     * @var float
     */
    private $postageBalance;

    /**
     * Total amount of postage printed (after re-credit) in dollars and cents
     * (mils).
     *
     * @var  float
     */
    private $ascendingBalance;

    /**
     * Account Status flag.
     *
     * @var string
     */
    private $accountStatus;

    /**
     * The account's 12 character Device ID (that appears in the indicium).
     *
     * @var string
     */
    private $deviceId;

    /**
     * Sets the Account ID for the Endicia postage account.
     *
     * The account id may not be empty and must be 7 or fewer characters in
     * length. If these requirements are not met an InvalidArgumenException
     * is thrown.
     *
     * @param string $accountId Account ID for the Endicia postage account.
     *
     * @throws InvalidArgumentException if the account id is invalid.
     */
    public function setAccountId(string $accountId) : void
    {
        if (empty($accountId)) {
            throw new InvalidArgumentException('Account ID is required.');
        }
        
        if (strlen($accountId) > 7) {
            throw new InvalidArgumentException('Account ID must be 7 or ' .
                'fewer characters in length.');
        }

        $this->accountId = $accountId;
    }

    /**
     * Returns the Account ID for the Endicia postage account.
     *
     * @return [type] [description]
     */
    public function getAccountId() : string
    {
        return $this->accountId;
    }

    /**
     * Sets the Serial Number of the last item created on the account.
     *
     * The serian number must be numeric and must be 12 characters in length.
     * If these requirements are not met an InvalidArgumentException is thrown.
     *
     * @param string $serialNumber The serial number of the last item created.
     *
     * @throws InvalidArgumentException If serial number is invalid.
     */
    public function setSerialNumber(string $serialNumber) : void
    {
        if (! is_numeric($serialNumber)) {
            throw new InvalidArgumentException(
                'Serial Number must be numeric.'
            );
        }

        if (strlen($serialNumber) > 12) {
            throw new InvalidArgumentException('Serial Number must be 12 or ' .
                'fewer characters.');
        }

        $this->serialNumber = $serialNumber;
    }

    /**
     * Returns the Serial Number of the last item created on the account.
     *
     * @return string Returns the serial number of the last item created.
     */
    public function getSerialNumber() : string
    {
        return $this->serialNumber;
    }

    /**
     * Sets the remaining postage balance after re-credit in dollars and cents
     * (mils).
     *
     * @param float $postageBalanace The remaining postage balanace.
     */
    public function setPostageBalance(float $postageBalanace) : void
    {
        $this->postageBalanace = $postageBalanace;
    }

    /**
     * Returns the remaining postage balance after re-credit in dollars and
     * cents (mils).
     *
     * @return float Returns the remaining postage balance.
     */
    public function getPostageBalance() : float
    {
        return $this->postageBalanace;
    }

    /**
     * Sets the total amount of postage printed (after re-credit) in dollars and
     * cents (mils).
     *
     * @param float $ascendingBalance The total amount of postage printed.
     */
    public function setAscendingBalance(float $ascendingBalance) : void
    {
        $this->ascendingBalance = $ascendingBalance;
    }

    /**
     * Returns the total amount of postage printed (after re-credit) in dollars
     * and cents (mils).
     *
     * @return float Returns the total amount of postage printed.
     */
    public function getAscendingBalance() : float
    {
        return $this->ascendingBalance;
    }

    /**
     * Sets the Account Status flag. The status should always be "A" for Active.
     *
     * @param string $accountStatus The account status flag.
     *
     * @throws InvalidArgumentException if the account status is invalid.
     */
    public function setAccountStatus(string $accountStatus) : void
    {
        if ($accountStatus != 'A') {
            throw new InvalidArgumentException('Invalid Account Status: ' .
                $accountStatus . '. Value must always be "A"');
        }
       
        $this->accountStatus = $accountStatus;
    }

    /**
     * Returns the account status flag. Should always be "A" for Active.
     *
     * @return Returns the acocunt status flag.
     */
    public function getAccountStatus() : string
    {
        return $this->accountStatus;
    }

    /**
     * Sets the account's 12 character Device ID (that appears in the indicium).
     *
     * The device id must be 12 characters in length. If this requirement is not
     * met an InvalidArgumentException is thrown.
     *
     * @param string $deviceId The account's twelve character device id.
     *
     * @throws InvalidArgumentException if device id is invalid.
     */
    public function setDeviceId(string $deviceId) : void
    {
        if (strlen($deviceId) != 12) {
            throw new InvalidArgumentException(
                'Device ID must be 12 characters.'
            );
        }

        $this->deviceId = $deviceId;
    }

    /**
     * Returns the account's 12 character Device ID (that appears in the
     * indicium).
     *
     * @return string Returns the account's 12 character device id.
     */
    public function getDeviceId() : string
    {
        return $this->deviceId;
    }
}
