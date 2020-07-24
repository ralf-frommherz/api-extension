<?php


namespace Cs\ApiExtensionBundle\Exception;

use Throwable;

/**
 * Marker interface
 * @package Cs\ApiExtensionBundle\Exception
 */
interface ApiExceptionInterface extends Throwable
{
    /**
     * @return int
     */
    public function getStatusCode(): int;
}