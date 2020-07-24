<?php


namespace Cs\ApiExtensionBundle\Api\Resolver;


use Cs\ApiExtensionBundle\Api\Request\ApiCollectionGetRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Class ApiCollectionGetRequestResolver
 * @package Cs\ApiExtensionBundle\Api\Request
 */
class ApiCollectionGetRequestResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $argument->getType() === ApiCollectionGetRequest::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield new ApiCollectionGetRequest(
            $request
        );
    }
}