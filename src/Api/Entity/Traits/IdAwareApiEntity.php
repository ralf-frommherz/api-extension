<?php

namespace Cs\ApiExtensionBundle\Api\Entity\Traits;

use Cs\ApiExtensionBundle\Api\Entity\Annotations\Identifier;

/**
 * Trait IdAwareApiEntity
 * @package Cs\ApiExtensionBundle\Api\Entity\Traits
 */
trait IdAwareApiEntity
{
    /**
     * @Identifier
     * @var string|null
     */
    private ?string $id = null;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }


}