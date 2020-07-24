<?php


namespace Cs\ApiExtensionBundle\Serializer;


use Cs\ApiExtensionBundle\Api\Entity\Annotations\ReferenceMany;
use Cs\ApiExtensionBundle\Api\Entity\Annotations\ReferenceOne;
use Cs\ApiExtensionBundle\Api\Entity\ApiEntity;
use Cs\ApiExtensionBundle\Service\InternalRequestService;
use Doctrine\Common\Annotations\Reader;
use Exception;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * This denormalizer is used to resolve references
 *
 * Class ApiEntityDenormalizer
 * @package Cs\ApiExtensionBundle\Serializer
 */
class ApiEntityDenormalizer implements DenormalizerInterface
{
    /**
     * @var ObjectNormalizer
     */
    private ObjectNormalizer $objectNormalizer;
    /**
     * @var Reader
     */
    private Reader $reader;
    /**
     * @var InternalRequestService
     */
    private InternalRequestService $internalRequestService;

    /**
     * ApiEntityDenormalizer constructor.
     * @param ObjectNormalizer $objectNormalizer
     * @param Reader $reader
     * @param InternalRequestService $internalRequestService
     */
    public function __construct(ObjectNormalizer $objectNormalizer, Reader $reader, InternalRequestService $internalRequestService)
    {
        $this->objectNormalizer = $objectNormalizer;
        $this->reader = $reader;
        $this->internalRequestService = $internalRequestService;
    }

    /**
     * Resolves relations
     *
     * @param mixed $data
     * @param string $type
     * @param string|null $format
     * @param array $context
     * @return array|object
     * @throws ReflectionException
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $reflectionClass = new ReflectionClass($type);

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();

            if(empty($data[$reflectionProperty->getName()]))
            {
                continue;
            }

            /** @var ReferenceOne|null $referenceOneAnnotation */
            $referenceOneAnnotation = $this->reader->getPropertyAnnotation($reflectionProperty, ReferenceOne::class);
            if($referenceOneAnnotation)
            {
                $data[$propertyName] = $this->objectNormalizer->normalize(
                    $this->internalRequestService->getOne($data[$propertyName])
                );
            }

            /** @var ReferenceMany|null $referenceManyAnnotation */
            $referenceManyAnnotation = $this->reader->getPropertyAnnotation($reflectionProperty, ReferenceMany::class);
            if($referenceManyAnnotation)
            {
                $data[$propertyName] = array_map(function (ApiEntity $entity){
                    return $this->objectNormalizer->normalize($entity);
                }, $this->internalRequestService->getList($data[$propertyName]));
            }
        }

        return $this->objectNormalizer->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_subclass_of($type, ApiEntity::class);
    }
}