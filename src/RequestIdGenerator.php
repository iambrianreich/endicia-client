<?php
/**
 * Created by PhpStorm.
 * User: breic
 * Date: 11/14/2018
 * Time: 12:02 PM
 */

namespace RWC\Endicia;

/**
 * Class RequestIdGenerator
 * @package RWC\Endicia
 * @todo Document
 */
class RequestIdGenerator
{
    public static function generateRequestId() : string
    {
        return uniqid();
    }
}