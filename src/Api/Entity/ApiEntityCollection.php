<?php


namespace Cs\ApiExtensionBundle\Api\Entity;

/**
 * Class ApiEntityCollection
 * @package Cs\ApiExtensionBundle\Api\Entity
 */
abstract class ApiEntityCollection
{
    /**
     * @var ApiEntity[]
     */
    private array $entities;
    /**
     * @var int
     */
    private int $totalSize;

    /**
     * ApiEntityCollection constructor.
     * @param ApiEntity[] $entities
     * @param int $totalSize
     */
    public function __construct(array $entities = [], int $totalSize = 0)
    {
        $this->entities = $entities;
        $this->totalSize = $totalSize;
    }

    /**
     * Returns the name of the api entity
     *
     * @return string
     */
    abstract public function getEntityClassName() : string ;

    /**
     * Adds an entity into the collection
     *
     * @param ApiEntity $entity
     */
    public function add(ApiEntity $entity): void
    {
        $this->entities[] = $entity;
    }

    /**
     * @return ApiEntity[]
     */
    public function getEntities(): array
    {
        return $this->entities;
    }

    /**
     * @return int
     */
    public function getTotalSize(): int
    {
        return $this->totalSize;
    }

    /**
     * @param int $totalSize
     */
    public function setTotalSize(int $totalSize): void
    {
        $this->totalSize = $totalSize;
    }
}