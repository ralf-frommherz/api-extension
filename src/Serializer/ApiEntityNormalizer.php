<?php


namespace Cs\ApiExtensionBundle\Serializer;

use Cs\ApiExtensionBundle\Api\Entity\Annotations\ReferenceMany;
use Cs\ApiExtensionBundle\Api\Entity\Annotations\ReferenceOne;
use Cs\ApiExtensionBundle\Api\Entity\ApiEntity;
use Cs\ApiExtensionBundle\Service\ApiRouteService;
use Cs\ApiExtensionBundle\Traits\ReflectionPropertyValueGetterTrait;
use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * This normalizer is used to resolve relations in api entities
 *
 * Class ApiEntityNormalizer
 * @package Cs\ApiExtensionBundle\Serializer
 */
class ApiEntityNormalizer implements NormalizerInterface
{
    use ReflectionPropertyValueGetterTrait;

    /**
     * @var ObjectNormalizer
     */
    private ObjectNormalizer $objectNormalizer;
    /**
     * @var Reader
     */
    private Reader $reader;
    /**
     * @var ApiRouteService
     */
    private ApiRouteService $apiRouteService;

    /**
     * ApiEntityNormalizer constructor.
     * @param ObjectNormalizer $objectNormalizer
     * @param Reader $reader
     * @param ApiRouteService $apiRouteService
     */
    public function __construct(ObjectNormalizer $objectNormalizer, Reader $reader, ApiRouteService $apiRouteService)
    {
        $this->objectNormalizer = $objectNormalizer;
        $this->reader = $reader;
        $this->apiRouteService = $apiRouteService;
    }

    /**
     * @inheritDoc
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        if(!$object instanceof ApiEntity)
        {
            throw new \RuntimeException(sprintf('"%s" not supported by this normalizer', get_class($object)));
        }

        $reflectionClass = new ReflectionClass($object);
        $replacements = [];
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            /** @var ReferenceOne|null $referenceOneAnnotation */
            $referenceOneAnnotation = $this->reader->getPropertyAnnotation($reflectionProperty, ReferenceOne::class);
            if($referenceOneAnnotation)
            {
                $entity = $this->getPropertyValue($object, $reflectionClass, $reflectionProperty);
                $path = $this->apiRouteService->getPathByEntity($entity);
                $replacements[$reflectionProperty->getName()] = $path;
            }

            /** @var ReferenceMany|null $referenceManyAnnotation */
            $referenceManyAnnotation = $this->reader->getPropertyAnnotation($reflectionProperty, ReferenceMany::class);
            if($referenceManyAnnotation)
            {
                $entities = $this->getPropertyValue($object, $reflectionClass, $reflectionProperty);
                $paths = $this->apiRouteService->getPathsByEntity($entities);
                $replacements[$reflectionProperty->getName()] = $paths;
            }
        }

        $normalizedObject = $this->objectNormalizer->normalize($object);
        $normalizedObject = array_replace($normalizedObject, $replacements);
        return $normalizedObject;
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof ApiEntity;
    }
}