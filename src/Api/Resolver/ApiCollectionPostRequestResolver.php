<?php


namespace Cs\ApiExtensionBundle\Api\Resolver;


use Cs\ApiExtensionBundle\Api\Request\ApiCollectionPostRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Class ApiCollectionPostRequestResolver
 * @package Cs\ApiExtensionBundle\Api\Request
 */
class ApiCollectionPostRequestResolver extends ApiRequestResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $argument->getType() === ApiCollectionPostRequest::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield new ApiCollectionPostRequest(
            $request,
            $this->getApiEntityByRequest($request)
        );
    }
}