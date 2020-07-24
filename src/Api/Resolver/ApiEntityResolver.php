<?php


namespace Cs\ApiExtensionBundle\Api\Resolver;

use Cs\ApiExtensionBundle\Api\Operations;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * This resolver can be used to resolve api entities
 * Class ApiEntityResolver
 * @package Cs\ApiExtensionBundle\Api\Request
 */
class ApiEntityResolver extends ApiRequestResolver implements ArgumentValueResolverInterface
{
    private const ALLOWED_OPERATIONS = [
        Operations::OPERATION_ITEM_PATCH,
        Operations::OPERATION_COLLECTION_POST,
    ];

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        $entityFqn = $request->attributes->get('entity');
        $operation = $request->attributes->get('operation');

        if(empty($entityFqn) || !in_array($operation, self::ALLOWED_OPERATIONS, true))
        {
            return false;
        }

        return $entityFqn === $argument->getType();
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield $this->getApiEntityByRequest($request);
    }
}