<?php


namespace Cs\ApiExtensionBundle\Api\Entity\Annotations;

use Cs\ApiExtensionBundle\Api\Operations;

/**
 * Class ReferenceOne
 * @package Cs\ApiExtensionBundle\Api\Entity\Annotations/**
 * @Annotation
 * @Target("ALL")
 */
class ReferenceOne
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