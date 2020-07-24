<?php


namespace Cs\ApiExtensionBundle\Api\Entity\Annotations;

use Cs\ApiExtensionBundle\Api\Operations;

/**
 * Class ReferenceMany
 * @package Cs\ApiExtensionBundle\Api\Entity\Annotations/**
 * @Annotation
 * @Target("ALL")
 */
class ReferenceMany
{
    /**
     * @see Operations
     * @var string
     */
    public string $type;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }
}