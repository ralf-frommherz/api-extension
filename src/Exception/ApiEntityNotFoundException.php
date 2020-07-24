<?php


namespace Cs\ApiExtensionBundle\Exception;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class ApiEntityNotFoundException
 * @package Cs\ApiExtensionBundle\Exception
 */
class ApiEntityNotFoundException extends AbstractApiException
{
    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }
}