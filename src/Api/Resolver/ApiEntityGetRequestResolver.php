<?php


namespace Cs\ApiExtensionBundle\Api\Resolver;


use Cs\ApiExtensionBundle\Api\Request\ApiEntityGetRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Class ApiEntityGetRequestResolver
 * @package Cs\ApiExtensionBundle\Api\Request
 */
class ApiEntityGetRequestResolver extends ApiRequestResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $argument->getType() === ApiEntityGetRequest::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield new ApiEntityGetRequest(
            $this->getIdByRequest($request),
            $request
        );
    }
}