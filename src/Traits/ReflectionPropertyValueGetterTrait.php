<?php


namespace Cs\ApiExtensionBundle\Traits;


use Cs\ApiExtensionBundle\Api\Entity\ApiEntity;
use ReflectionClass;
use ReflectionProperty;

trait ReflectionPropertyValueGetterTrait
{
    private function getPropertyValue(ApiEntity $apiEntity, ReflectionClass $reflectionClass, ReflectionProperty $reflectionProperty)
    {
        $propertyName = $reflectionProperty->getName();
        $methodName = 'get'.ucfirst($propertyName);
        if($reflectionClass->hasMethod($methodName))
        {
            return $reflectionClass->getMethod($methodName)->invoke($apiEntity);
        }

        $methodName = 'is'.ucfirst($propertyName);
        if($reflectionClass->hasMethod($methodName))
        {
            return $reflectionClass->getMethod($methodName)->invoke($apiEntity);
        }

        return null;
    }
}