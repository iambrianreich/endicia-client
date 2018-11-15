<?php


namespace RWC\Endicia;

/**
 * Class ResetSuspendedAccountResponse
 * @package RWC\Endicia
 * @todo Document
 */
class ResetSuspendedAccountResponse extends AbstractResponse
{
    /**
     * @param string $xml
     * @param AbstractResponse|null $response
     * @return AbstractResponse
     * @throws InvalidArgumentException
     * @todo Document
     */
    public static function fromXml(string $xml, AbstractResponse $response = null) : AbstractResponse
    {
        // Force an object.
        $response = $response ?? new ResetSuspendedAccountResponse();

        return parent::fromXml($xml, $response);
    }
}
