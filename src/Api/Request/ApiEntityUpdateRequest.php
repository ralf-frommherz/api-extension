<?php


namespace Cs\ApiExtensionBundle\Api\Request;


use Cs\ApiExtensionBundle\Api\Entity\ApiEntity;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ItemUpdateRequest
 * @package Cs\ApiExtensionBundle\Api\Request
 */
class ApiEntityUpdateRequest extends ApiRequest
{
    /**
     * @var string
     */
    private string $id;
    /**
     * @var ApiEntity
     */
    private ApiEntity $apiEntity;

    /**
     * CollectionPostRequest constructor.
     * @param string $id
     * @param ApiEntity $apiEntity
     * @param Request $request
     */
    public function __construct(string $id, ApiEntity $apiEntity, Request $request)
    {
        parent::__construct($request);
        $this->id = $id;
        $this->apiEntity = $apiEntity;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return ApiEntity
     */
    public function getApiEntity(): ApiEntity
    {
        return $this->apiEntity;
    }
}