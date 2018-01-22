<?php


namespace RWC\Endicia;

use RWC\Endicia\InvalidArgumentException;
use RWC\Endicia\AbstractResponse;

class ChangePassPhraseRequestResponse extends AbstractResponse
{
    /**
     * If requested, contains the token.
     *
     * @var string
     */
    private $token;

    /**
     * Sets the token.
     *
     * The ChangePassPhraseRequest can specify an option called TokenRequested.
     * When true, the ChangePassPhraseRequest will ask that the password be
     * changed AND return a token, which can be used to authenticate requests
     * instead of passing the account id and password in every request.
     *
     * The token must be 50 or fewer characters.
     *
     * @param string $token The token.
     *
     * @throws InvalidArgumentException if the token is invalid.
     */
    public function setToken(string $token) : void
    {
        if (empty($token)) {
            throw new InvalidArgumentException("Token cannot be empty.");
        }

        if (strlen($token) > 50) {
            throw new InvalidArgumentException(
                "Token must be 50 or fewer characters"
            );
        }

        $this->token = $token;
    }

    /**
     * Returns the Security Token.
     *
     * @return string|null Returns the security token.
     */
    public function getToken() : ?string
    {
        return $this->token;
    }

    public static function fromXml(string $xml, AbstractResponse $response = null) : AbstractResponse
    {
        // Force an object.
        $response = $response ?? new ChangePassPhraseRequestResponse();

        try {
            parent::fromXml($xml, $response);

            $xml = str_replace('www.envmgr.com/LabelService', '', $xml);
            $dom = new \DOMDocument();
            $dom->loadXML($xml);
            
            // If it's a failure we're done.
            if (! $response->isSuccessful()) {
                return $response;
            }

            $token = $dom->getElementsByTagName("Token");

            // If a token was specified, set it.
            if ($token->length > 0) {
                $response->setToken($token[0]->nodeValue);
            }
            
            return $response;
        } catch (\Exception $e) {
            throw new InvalidArgumentException(
                "Invalid ChangePassPhraseRequestResponse XML. " .
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
