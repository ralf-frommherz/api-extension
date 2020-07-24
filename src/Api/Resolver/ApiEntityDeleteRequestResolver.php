<?php


namespace Cs\ApiExtensionBundle\Api\Resolver;


use Cs\ApiExtensionBundle\Api\Request\ApiEntityDeleteRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Class ApiEntityDeleteRequestResolver
 * @package Cs\ApiExtensionBundle\Api\Request
 */
class ApiEntityDeleteRequestResolver extends ApiRequestResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $argument->getType() === ApiEntityDeleteRequest::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield new ApiEntityDeleteRequest(
            $this->getIdByRequest($request),
            $request
        );
    }
}