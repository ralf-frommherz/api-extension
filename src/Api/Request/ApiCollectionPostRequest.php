<?php


namespace Cs\ApiExtensionBundle\Api\Request;

use Cs\ApiExtensionBundle\Api\Entity\ApiEntity;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CollectionPostRequest
 * @package Cs\ApiExtensionBundle\Api\Request
 */
class ApiCollectionPostRequest extends ApiRequest
{
    /**
     * @var ApiEntity
     */
    private ApiEntity $apiEntity;

    /**
     * CollectionPostRequest constructor.
     * @param Request $request
     * @param ApiEntity $apiEntity
     */
    public function __construct(Request $request, ApiEntity $apiEntity)
    {
        parent::__construct($request);
        $this->apiEntity = $apiEntity;
    }

    /**
     * @return ApiEntity
     */
    public function getApiEntity(): ApiEntity
    {
        return $this->apiEntity;
    }
}