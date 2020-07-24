<?php


namespace Cs\ApiExtensionBundle\Serializer;

use Cs\ApiExtensionBundle\Api\Entity\ApiEntity;
use Cs\ApiExtensionBundle\Api\Entity\ApiEntityCollection;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class ApiEntityCollectionNormalizer
 * @package Cs\ApiExtensionBundle\Serializer
 */
class ApiEntityCollectionNormalizer implements NormalizerInterface
{
    /**
     * @var ApiEntityNormalizer
     */
    private ApiEntityNormalizer $apiEntityNormalizer;

    /**
     * ApiEntityCollectionNormalizer constructor.
     * @param ApiEntityNormalizer $apiEntityNormalizer
     */
    public function __construct(ApiEntityNormalizer $apiEntityNormalizer)
    {
        $this->apiEntityNormalizer = $apiEntityNormalizer;
    }

    /**
     * @inheritDoc
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        if(!$object instanceof ApiEntityCollection)
        {
            throw new \RuntimeException(sprintf('"%s" not supported by this normalizer', get_class($object)));
        }

        return array_map(function (ApiEntity $entity) {
            return $this->apiEntityNormalizer->normalize($entity);
        }, $object->getEntities());
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, string  $format = null)
    {
        return $data instanceof ApiEntityCollection;
    }
}