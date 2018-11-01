<?php

namespace RWC\Endicia;

use DOMDocument;
use RWC\Endicia\AbstractRequest;

/**
 * API request to change a postage account's pass phrase.
 *
 * An API request to change a postage account's pass phrase. This request
 * requires a new passphrase to be specified. The passphrase cannot be empty and
 * must be 64 or fewer characters in length. If these conditions are not met,
 * an InvalidArgumentException is thrown.
 */
class ChangePassphraseRequest extends AbstractRequest
{
    /**
     * The new passphrase to set for the account.
     *
     * @var string
     */
    private $newPassPhrase;

    /**
     * Flag that specifies if a token is being requested.
     *
     * @var bool
     */
    private $tokenRequested;

    /**
     * Creates a new ChangePassphraseRequest.
     *
     * @param string $requesterId The requester's id.
     * @param CertifiedIntermediary $certifiedIntermediary The postage account credential.
     * @param string $newPassPhrase The new pass phrase.
     * @param bool|boolean $tokenRequested True to request a security token.
     * @throws InvalidArgumentException
     */
    public function __construct(
        string $requesterId,
        CertifiedIntermediary $certifiedIntermediary,
        string $newPassPhrase,
        bool $tokenRequested = false
    ) {
        parent::__construct($requesterId, $certifiedIntermediary);
        $this->setNewPassPhrase($newPassPhrase);
        $this->setTokenRequested($tokenRequested);
    }

    /**
     * Sets the new PassPhrase.
     *
     * The new pass phrase cannot be empty, and must not exceed 64 characters in
     * length. If any of these conditions are true an InvalidArgumentException
     * is thrown.
     *
     * @param string $newPassPhrase The new passphrase.
     *
     * @throws InvalidArgumentException if the new passphrase is invalid.
     */
    public function setNewPassPhrase(string $newPassPhrase) : void
    {
        if (empty($newPassPhrase)) {
            throw new InvalidArgumentException(
                'New pass phrase cannot be empty.'
            );
        }

        if (strlen($newPassPhrase) > 64) {
            throw new InvalidArgumentException(
                'New pass phrase cannot be longer than 64 characters.'
            );
        }

        $this->newPassPhrase = $newPassPhrase;
    }

    /**
     * Returns the new passphrase.
     *
     * @return string Returns the new passphrase.
     */
    public function getNewPassPhrase() : string
    {
        return $this->newPassPhrase;
    }

    /**
     * Sets the token requested flag.
     *
     * Set to true if the ChangePassphraseRequest is requesting a security token
     * in the response.
     *
     * @param bool $tokenRequested True if requesting a security token.
     */
    public function setTokenRequested(bool $tokenRequested) : void
    {
        $this->tokenRequested = $tokenRequested;
    }

    /**
     * Returns true if requesting a security token.
     *
     * @return bool Returns true if requesting a security token.
     */
    public function getTokenRequested() : bool
    {
        return $this->tokenRequested;
    }

    /**
     * Returns the XML for the ChangePassphraseRequest.
     *
     * @return string Returns the ChangePassphraseRequest XML.
     */
    public function toXml() : string
    {
        return $this->toDOMDocument()->saveXML();
    }

    /**
     * @return DOMDocument
     */
    public function toDOMDocument(): DOMDocument
    {
        $document = new DOMDocument();

        $root = $document->createElement('ChangePassPhraseRequest');
        $document->appendChild($root);

        $root->setAttribute('TokenRequested',
            $this->getTokenRequested() ? 'true' : 'false'
        );

        $root->appendChild($document->createElement('RequesterID', $this->getRequesterId()));
        $root->appendChild($document->createElement('RequestID', $this->getRequestId()));
        $root->appendChild($this->getCertifiedIntermediary()->toDOMElement($document));

        $root->appendChild($document->createElement('NewPassPhrase', $this->getNewPassPhrase()));

        return $document;
    }
}
