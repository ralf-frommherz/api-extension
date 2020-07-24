<?php


namespace Cs\ApiExtensionBundle\Api\Response;


use Cs\ApiExtensionBundle\Api\Entity\ApiEntityCollection;

/**
 * Class CollectionResponse
 * @package Cs\ApiExtensionBundle\Api\Response
 */
class ApiCollectionResponse extends ApiResponse
{
    /**
     * @var ApiEntityCollection
     */
    private ApiEntityCollection $apiEntityCollection;

    /**
     * CollectionResponse constructor.
     * @param ApiEntityCollection $apiEntityCollection
     */
    public function __construct(ApiEntityCollection $apiEntityCollection)
    {
        parent::__construct();
        $this->apiEntityCollection = $apiEntityCollection;
    }

    /**
     * @return ApiEntityCollection
     */
    public function getApiEntityCollection(): ApiEntityCollection
    {
        return $this->apiEntityCollection;
    }
}