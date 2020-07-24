<?php


namespace Cs\ApiExtensionBundle\Serializer;

use Cs\ApiExtensionBundle\Api\Response\ApiEntityDeleteResponse;
use Cs\ApiExtensionBundle\Api\Response\ApiEntityResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class ItemResponseNormalizer
 * @package Cs\ApiExtensionBundle\Serializer
 */
class ApiEntityResponseNormalizer implements NormalizerInterface
{
    /**
     * @var ApiEntityNormalizer
     */
    private ApiEntityNormalizer $apiEntityNormalizer;

    /**
     * ItemResponseNormalizer constructor.
     * @param ApiEntityNormalizer $apiEntityNormalizer
     */
    public function __construct(ApiEntityNormalizer $apiEntityNormalizer)
    {
        $this->apiEntityNormalizer = $apiEntityNormalizer;
    }

    /**
     * @inheritDoc
     */
    public function normalize($object, $format = null, array $context = [])
    {
        if(!$object instanceof ApiEntityResponse)
        {
            throw new \RuntimeException(sprintf('"%s" not supported by this normalizer', get_class($object)));
        }

        return $this->apiEntityNormalizer->normalize($object->getEntity());
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof ApiEntityResponse;
    }
}