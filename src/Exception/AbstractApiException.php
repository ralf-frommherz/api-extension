<?php


namespace Cs\ApiExtensionBundle\Exception;


use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiResponseException
 * @package Cs\ApiExtensionBundle\Exception
 */
abstract class AbstractApiException extends RuntimeException implements ApiExceptionInterface
{
    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}