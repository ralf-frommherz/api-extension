<?php


namespace Cs\ApiExtensionBundle\Serializer;


use Cs\ApiExtensionBundle\Api\Response\ApiCollectionResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class CollectionResponseNormalizer
 * @package Cs\ApiExtensionBundle\Serializer
 */
class ApiCollectionResponseNormalizer implements NormalizerInterface
{
    /**
     * @var ApiEntityCollectionNormalizer
     */
    private ApiEntityCollectionNormalizer $apiEntityCollectionNormalizer;

    /**
     * CollectionResponseNormalizer constructor.
     * @param ApiEntityCollectionNormalizer $apiEntityCollectionNormalizer
     */
    public function __construct(ApiEntityCollectionNormalizer $apiEntityCollectionNormalizer)
    {
        $this->apiEntityCollectionNormalizer = $apiEntityCollectionNormalizer;
    }

    /**
     * @inheritDoc
     */
    public function normalize($object, $format = null, array $context = [])
    {
        if(!$object instanceof ApiCollectionResponse)
        {
            throw new \RuntimeException(sprintf('"%s" not supported by this normalizer', get_class($object)));
        }

        return [
            'items' => $this->apiEntityCollectionNormalizer->normalize(
                $object->getApiEntityCollection()
            ),
            'total' => $object->getApiEntityCollection()->getTotalSize()
        ];
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof ApiCollectionResponse;
    }
}