<?php


namespace Cs\ApiExtensionBundle\Api\Resolver;


use Cs\ApiExtensionBundle\Api\Request\ApiEntityUpdateRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Class ApiEntityUpdateRequestResolver
 * @package Cs\ApiExtensionBundle\Api\Request
 */
class ApiEntityUpdateRequestResolver extends ApiRequestResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $argument->getType() === ApiEntityUpdateRequest::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield new ApiEntityUpdateRequest(
            $this->getIdByRequest($request),
            $this->getApiEntityByRequest($request),
            $request
        );
    }
}