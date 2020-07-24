<?php


namespace Cs\ApiExtensionBundle\Api\Controller\Annotations;

/**
 * Class ApiController
 * @package Cs\ApiExtensionBundle\Api\Controller\Annotations/**
 * @Annotation
 * @Target("ALL")
 */
class ApiController
{
    /**
     * Name of the entity that is handled by this controller
     * @var string
     */
    public string $entity;

    /**
     * Name of the api route
     * @var string
     */
    public ?string $name = null;

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @param string $entity
     */
    public function setEntity(string $entity): void
    {
        $this->entity = $entity;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}