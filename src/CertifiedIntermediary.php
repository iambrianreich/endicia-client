<?php

/**
 * This file contains the RWC\Endicia\CertifiedIntermediary class.
 *
 * @author     Brian Reich <breich@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia;

use DOMDocument;
use DOMElement;

/**
 * CertifiedIntermediary provides Endicia Postage Account credentials.
 *
 * The CertifiedIntermediary type provides Endicia Postage Account credentials
 * for API requests. Requests can be authenticated in two different ways: via
 * a set of account credentials including an account id and passphrase, or via
 * a security token.
 *
 * A CertifiedIntermediary may not be created directly via it's constructor. To
 * create an instance you will call one either createFromCredentials() or
 * createFromToken(), depending on the type of credential you wish to use to
 * authenticate.
 *
 * When using a set of account credentials, an accountId may not be blank and
 * must be no more than 7 characters in length. A passphrase may not be blank
 * and may be no more than 64 characters in length.
 *
 * When using a security token, the token may not be blank and cannot exceed
 * 150 characters in length.
 *
 * Passing invalid credentials or tokens to these factory methods will result
 * an an InvalidArgumentException being thrown.
 *
 * On success, the factory methods will return a valid CertifiedIntermediary.
 *
 * @author     Brian Reich <breich@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */
class CertifiedIntermediary implements IRequestElement
{
    /**
     * The account id for the Endicia postage account.
     *
     * @var  string
     */
    protected $accountId;

    /**
     * The passphrase for the Endicia account.
     *
     * @var string
     */
    protected $passPhrase;

    /**
     * The security token for the Endicia account. Use only if your account is
     * set up to use the Token. If using the Token, DO NOT supply the AccountID
     * and pass phrase elements.
     *
     * @var string
     */
    protected $token;

    /**
     * Returns the Endicia postage account's account id, or null if using token.
     *
     * @return string|null Returns the account id or null if using a token.
     */
    public function getAccountId() : ?string
    {
        return $this->accountId;
    }

    /**
     * Returns the Endicia account's pass phrase or null if using token.
     *
     * @return string|null Returns the Endicia account's pass phrase.
     */
    public function getPassPhrase() : ?string
    {
        return $this->passPhrase;
    }

    /**
     * Returns the token, or null if using credentials.
     *
     * @return string|NULL Returns the token.
     */
    public function getToken() : ?string
    {
        return $this->token;
    }

    /**
     * Returns the CertifiedIntermediary XML as an XML block.
     *
     * The CertifiedIntermediary will be returned with an XML block containing
     * either a Token tag or a set of AccountID and PassPhrase tags, depending
     * on how the CertifiedIntermediary was created and configured.
     *
     * @return string Returns the CertifiedIntermediary as an XML block.
     */
    public function toXml() : string
    {
        $document = new DOMDocument();
        $changePassPhraseElement = $this->toDOMElement($document);
        return $document->saveXML($changePassPhraseElement);
    }

    /**
     * Returns the element as a DOMElement created from a given DOM.
     *
     * @param \DOMDocument $document The DOMDocument used to create the element.
     * @return DOMElement Returns the generated DOMElement.
     */
    public function toDOMElement(\DOMDocument $document) : DOMElement
    {
        $ciEl = $document->createElement('CertifiedIntermediary');

        // If a token is being used to authenticate, use it.
        if (! empty($this->getToken())) {
            // Use Token
            $ciEl->appendChild($document->createElement('Token', $this->getToken()));
            return $ciEl;
        }

        // Otherwise, use credentials.
        $ciEl->appendChild($document->createElement(
            'AccountID',
            $this->getAccountId())
        );


        $ciEl->appendChild($document->createElement(
            'PassPhrase',
            $this->getPassPhrase())
        );

        return $ciEl;
    }
    /**
     * Creates a new CertifiedIntermediary from a security token.
     *
     * Creates a new CertifiedIntermediary from a security token. Specify a
     * valid security token.
     *
     * @param  string $token The Endicia postage account security token.
     *
     * @return CertifiedIntermediary Returns the CertifiedIntermediary.
     * @throws InvalidArgumentException if the security token is invalid.
     */
    public static function createFromToken(string $token) : CertifiedIntermediary
    {
        $certifiedIntermediary = new CertifiedIntermediary();
        $certifiedIntermediary->setToken($token);

        return $certifiedIntermediary;
    }

    /**
     * Creates a new CertifiedIntermediary from a set of account credentials.
     *
     * Creates a new CertifiedIntermediary from a set of account credentials.
     * Specify a valid Endicia postage account id and the correct passphrase for
     * that account.
     *
     * @param  string $accountId  The Endicia postage account id.
     * @param  string $passPhrase The passphrase for the Endicia postage account.
     *
     * @return CertifiedIntermediary Returns the CertifiedIntermediary.
     * @throws InvalidArgumentException if accountId or pass phrase are not valid.
     */
    public static function createFromCredentials(string $accountId, string $passPhrase) : CertifiedIntermediary
    {
        $certifiedIntermediary = new CertifiedIntermediary();
        $certifiedIntermediary->setAccountId($accountId);
        $certifiedIntermediary->setPassPhrase($passPhrase);

        return $certifiedIntermediary;
    }

    /**
     * Prevents direct instantiation. Instantiate through create*() methods.
     */
    protected function __construct()
    {
    }

    /**
     * Sets the Endicia postage account's account id.
     *
     * The account id cannot be empty and must be 7 or fewer characters in
     * length.
     *
     * @param string $accountId The Endicia postage account's account id.
     *
     * @throws InvalidArgumentException if the account id is invalid.
     */
    protected function setAccountId(string $accountId) : void
    {
        if (empty($accountId)) {
            throw new InvalidArgumentException('AccountID cannot be empty.');
        }

        if (strlen($accountId) > 7) {
            throw new InvalidArgumentException(
                'AccountID must be 7 or fewer character.'
            );
        }

        $this->accountId = $accountId;
    }

    /**
     * Sets the Endicia postage account's pass phrase.
     *
     * Sets the Endicia postage account's pass phrase. The pass phrase cannot be
     * empty and must be 64 or fewer characters.
     *
     * @param string $passPhrase The Endicia postage account's pass phrase.
     *
     * @throws InvalidArgumentException if the passphrase is empty or too long.
     */
    protected function setPassPhrase(string $passPhrase) : void
    {
        if (empty($passPhrase)) {
            throw new InvalidArgumentException('Passphrase cannot be empty.');
        }

        if (strlen($passPhrase) > 64) {
            throw new InvalidArgumentException(
                'Passphrase must be <= 64 characters.'
            );
        }

        $this->passPhrase = $passPhrase;
    }
    
    /**
     * Sets the Endicia account token.
     *
     * The token cannot be null or empty. The token must be 150 characters in
     * length to be considered valid.
     *
     * @param string $token The Endicia account token.
     *
     * @throws InvalidArgumentException if token is empty or invalid length.
     */
    protected function setToken(string $token) : void
    {
        if (empty($token)) {
            throw new InvalidArgumentException('Token cannot be empty.');
        }

        if (strlen($token) > 150) {
            throw new InvalidArgumentException(
                'Token must be < 150 characters.'
            );
        }

        $this->token = $token;
    }
}
