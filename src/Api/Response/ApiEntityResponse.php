<?php


namespace Cs\ApiExtensionBundle\Api\Response;


use Cs\ApiExtensionBundle\Api\Entity\ApiEntity;

/**
 * Class ItemResponse
 * @package Cs\ApiExtensionBundle\Api\Response
 */
class ApiEntityResponse extends ApiResponse
{
    /**
     * @var ApiEntity
     */
    private ApiEntity $entity;

    /**
     * ItemResponse constructor.
     * @param ApiEntity $entity
     */
    public function __construct(ApiEntity $entity)
    {
        parent::__construct();
        $this->entity = $entity;
    }

    /**
     * @return ApiEntity
     */
    public function getEntity(): ApiEntity
    {
        return $this->entity;
    }
}