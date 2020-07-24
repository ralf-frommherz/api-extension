<?php


namespace Cs\ApiExtensionBundle\Api\Controller\Annotations;

use Cs\ApiExtensionBundle\Api\Operations;

/**
 * Class Operation
 * @package Cs\ApiExtensionBundle\Api\Controller\Annotations/**
 * @Annotation
 * @Target("ALL")
 */
class Operation
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