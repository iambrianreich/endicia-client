<?php

/**
 * This file contains the RWC\Endicia\Testing\ApiTestCase class.
 *
 * @author     Brian Reich <breich@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia\Testing;

use PHPUnit\Framework\TestCase;
use RWC\Endicia\CertifiedIntermediary;

/**
 * Extends PHPUnit TestCase with useful methods for Endicia library testing.
 *
 * @author     Brian Reich <breich@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */
abstract class ApiTestCase extends TestCase
{
    /**
     * The environment variable for passphrase configuration.
     */
    const PASSPHRASE_VARIABLE = 'ENDICIA_PASSPHRASE';

    /**
     * The environment variable for account configuration.
     */
    const ACCOUNTID_VARIABLE = 'ENDICIA_ACCOUNT_ID';

    /**
     * The environment variable for skipping change of account pass phrase.
     */
    const SKIP_CHANGE_PASSPHRASE_VARIABLE = 'ENDICIA_SKIP_CHANGE_PASSPHRASE';

    /**
     * Only run API tests if Endicia configuration is available.
     */
    protected function setUp()
    {
        if (! $this->isEndiciaConfigured()) {
            $this->markTestSkipped(
                'Endicia configuration is required to run API tests. Ensure that ' .
                'the following environment variables have correct values: ' .
                self::ACCOUNTID_VARIABLE . ', ' . self::PASSPHRASE_VARIABLE
            );
        }
    }

    protected function assertXPathExists(string $xml, string $query)
    {
        // Make DOMDocument behave.
        $dom = new \DOMDocument();
        $dom->loadXML($xml);

        $xp = new \DOMXPath($dom);
        $query = $xp->query($query);
        $this->assertTrue($query->length > 0);
    }

    /**
     * Returns the value of the ENDICIA_ACCOUNT_ID environment variable or null.
     *
     * @return string|null Returns value if ENDICIA_ACCOUNT_ID or null.
     */
    protected function getAccountId() : ?string
    {
        return getenv(self::ACCOUNTID_VARIABLE) ?? null;
    }

    /**
     * Returns true if the environment has an account id configured.
     *
     * @return boolean Returns true if the account id is configured.
     */
    protected function hasAccountId() : bool
    {
        return $this->getAccountId() != null;
    }

    /**
     * Returns the value of the ENDICIA_PASSPHRASE environment variable or null.
     *
     * @return string|null Returns value if ENDICIA_PASSPHRASE or null.
     */
    protected function getPassPhrase() : ?string
    {
        return getenv(self::PASSPHRASE_VARIABLE) ?? null;
    }

    /**
     * Returns true if a passphrase is configured.
     *
     * @return boolean Returns true if a passphrase is configured.
     */
    protected function hasPassPhrase() : ?string
    {
        return $this->getPassPhrase() != null;
    }

    /**
     * Returns the CertifiedIntermediary for the configured credentials.
     *
     * @return CertifiedIntermediary Returns the CertifiedIntermediary.
     */
    protected function getCertifiedIntermediary() : CertifiedIntermediary
    {
        return CertifiedIntermediary::createFromCredentials(
            $this->getAccountId(),
            $this->getPassPhrase()
        );
    }

    /**
     * Returns true if the test for changing the pass phrase should be skipped.
     *
     * @return boolean Returns true if changing pass phrase should be skipped.
     */
    protected function isSkipChangePassPhrase() : bool
    {
        return getenv(self::SKIP_CHANGE_PASSPHRASE_VARIABLE) !== false;
    }

    /**
     * Returns true if the environment can test API calls.
     *
     * Returns true if the environment is setup with both an account id and
     * a passphrase for testing.
     *
     * @return boolean Returns true if the environment is setup for testing.
     */
    protected function isEndiciaConfigured() : bool
    {
        return
            $this->hasAccountId() &&
            $this->hasPassPhrase();
    }
}
