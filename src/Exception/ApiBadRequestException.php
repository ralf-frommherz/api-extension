<?php


namespace Cs\ApiExtensionBundle\Exception;


use Symfony\Component\HttpFoundation\Response;

class ApiBadRequestException extends AbstractApiException
{
    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}