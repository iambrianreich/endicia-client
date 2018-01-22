<?php

namespace RWC\Endicia;

class UserSignupRequest
{
    private $tokenRequested;

    public function __construct($tokenRequested = false)
    {
        $this->setTokenRequested($tokenRequested);
    }
    public function setTokenRequested(bool $tokenRequested) : void
    {
        $this->tokenRequested = $tokenRequested;
    }

    public function getTokenRequested() : bool
    {
        return $this->tokenRequested;
    }

    public function toXml() : string
    {
    }
}
