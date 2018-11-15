<?php
/**
 * Created by PhpStorm.
 * User: breic
 * Date: 11/14/2018
 * Time: 11:52 AM
 */

namespace RWC\Endicia;


use DOMDocument;

class ResetSuspendedAccountRequest extends AbstractRequest implements IXMLRequest
{
    /**
     * Requester ID (also called Partner ID) uniquely identifies the partner
     * making the request. Endicia assigns this ID.
     *
     * @var string
     */
    protected $requesterId;

    /**
     * Request ID that uniquely identifies this request. This will be returned
     * in response.
     *
     * @var string
     */
    protected $requestId;

    /**
     * Account ID for the Endicia postage account.
     *
     * @var int
     */
    protected $accountId;

    /**
     * Answer to the challenge question on file for the Endicia postage account.
     *
     * @var string
     */
    protected $challengeAnswer;


    /**
     * New Pass Phrase for the Endicia postage account. The Pass Phrase must be
     * at least 5 characters long with a maximum of 64 characters. For added
     * security, the Pass Phrase should be at least 10 characters long and
     * include more than one word, use at least one uppercase and lowercase
     * letter, one number, and one non-text character (for example,
     * punctuation). A Pass Phrase which has been used previously will be
     * rejected.
     *
     * @var string
     */
    protected $newPassPhrase;

    /**
     * ResetSuspendedAccountRequest constructor.
     * @param string $requesterId
     * @param null|string $requestId
     * @param int $accountId
     * @param string $challengeAnswer
     * @param string $newPassPhrase
     * @throws InvalidArgumentException
     */
    public function __construct(
        string $requesterId,
        int $accountId,
        string $challengeAnswer,
        string $newPassPhrase,
        ?string $requestId = null
    ) {
        /** @noinspection PhpUnhandledExceptionInspection */
        parent::__construct(
            $requesterId,
            CertifiedIntermediary::createFromCredentials(
                'bogus',
                'bogus'
            )
        );

        $this->requestId = $requestId;
        $this->accountId = $accountId;
        $this->challengeAnswer = $challengeAnswer;
        $this->newPassPhrase = $newPassPhrase;
    }

    /**
     * Requester ID (also called Partner ID) uniquely identifies the partner
     * making the request. Endicia assigns this ID.
     *
     * @return string Returns the requester id.
     */
    public function getRequesterId(): string
    {
        return $this->requesterId;
    }

    /**
     * Requester ID (also called Partner ID) uniquely identifies the partner
     * making the request. Endicia assigns this ID.
     *
     * @param string $requesterId
     */
    public function setRequesterId(string $requesterId): void
    {
        $this->requesterId = $requesterId;
    }

    /**
     * Request ID that uniquely identifies this request. This will be returned
     * in response.
     *
     * @return string Returns the request id.
     */
    public function getRequestId(): string
    {
        return $this->requestId ?? RequestIdGenerator::generateRequestId();
    }

    /**
     * Request ID that uniquely identifies this request. This will be returned
     * in response. If no request id is specified one will be generated.
     *
     * @param null|string $requestId
     */
    public function setRequestId(?string $requestId): void
    {
        $this->requestId = $requestId;
    }

    /**
     * Account ID for the Endicia postage account.
     *
     * @return int Returns the account id.
     */
    public function getAccountId(): int
    {
        return $this->accountId;
    }

    /**
     * Account ID for the Endicia postage account.
     *
     * @param int $accountId Sets the account id.
     */
    public function setAccountId(int $accountId): void
    {
        $this->accountId = $accountId;
    }

    /**
     * Answer to the challenge question on file for the Endicia postage account.
     *
     * @return string Returns the channenge answer.
     */
    public function getChallengeAnswer(): string
    {
        return $this->challengeAnswer;
    }

    /**
     * Answer to the challenge question on file for the Endicia postage account.
     *
     * @param string $challengeAnswer Sets the challenge answer.
     */
    public function setChallengeAnswer(string $challengeAnswer): void
    {
        $this->challengeAnswer = $challengeAnswer;
    }

    /**
     * New Pass Phrase for the Endicia postage account. The Pass Phrase must be
     * at least 5 characters long with a maximum of 64 characters. For added
     * security, the Pass Phrase should be at least 10 characters long and
     * include more than one word, use at least one uppercase and lowercase
     * letter, one number, and one non-text character (for example,
     * punctuation). A Pass Phrase which has been used previously will be
     * rejected.
     *
     * @return string Returns the new passphrase.
     */
    public function getNewPassPhrase(): string
    {
        return $this->newPassPhrase;
    }

    /**
     * New Pass Phrase for the Endicia postage account. The Pass Phrase must be
     * at least 5 characters long with a maximum of 64 characters. For added
     * security, the Pass Phrase should be at least 10 characters long and
     * include more than one word, use at least one uppercase and lowercase
     * letter, one number, and one non-text character (for example,
     * punctuation). A Pass Phrase which has been used previously will be
     * rejected.
     *
     * @param string $newPassPhrase Sets the new passphrase.
     */
    public function setNewPassPhrase(string $newPassPhrase): void
    {
        $this->newPassPhrase = $newPassPhrase;
    }


    /**
     * Returns the IXMLRequest as an XML string.
     *
     * @return string Returns the element as an XML string.
     */
    public function toXml(): string
    {
        return $this->toDOMDocument()->saveXML();
    }

    /**
     * Returns IXMLRequest as a DOMDocument
     *
     * @return DOMDocument Returns the request as a DOMDocument.
     */
    public function toDOMDocument(): DOMDocument
    {
        $dom = new DOMDocument();
        $root = $dom->createElement('ResetSuspendedAccountRequest');
        $dom->appendChild($root);

        $root->appendChild($dom->createElement('RequesterID', $this->getRequesterId()));
        $root->appendChild($dom->createElement('RequestID', $this->getRequestId()));
        $root->appendChild($dom->createElement('AccountID', $this->getAccountId()));
        $root->appendChild($dom->createElement('ChallengeAnswer', $this->getChallengeAnswer()));
        $root->appendChild($dom->createElement('NewPassPhrase', $this->getNewPassPhrase()));

        return $dom;
    }
}