<?php


namespace Cs\ApiExtensionBundle\Traits;

use Cs\ApiExtensionBundle\Exception\ApiEntityNotFoundException;

/**
 * Trait ExceptionAwareApiController
 * @package Cs\ApiExtensionBundle\Traits
 */
trait ExceptionAwareApiController
{
    /**
     * Throws an exception if the given entity is null
     *
     * @param string $id
     * @param object|null $entity
     * @return object
     */
    private function throwApiResponseEntityNotFoundExceptionIfNull(string $id, ?object $entity): object
    {
        if($entity === null)
        {
            $this->throwApiResponseEntityNotFoundException($id);
        }

        return $entity;
    }

    /**
     * Throws ApiResponseEntityNotFoundException
     * @param string $id
     */
    private function throwApiResponseEntityNotFoundException(string $id): void
    {
        throw new ApiEntityNotFoundException(sprintf(
            'Entity with id "%s" not found', $id
        ));
    }
}