<?php


namespace Cs\ApiExtensionBundle\Api\Entity;

/**
 * Class ApiEntity
 * @package Cs\ApiExtensionBundle\Api\Entity
 */
abstract class ApiEntity
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }
}