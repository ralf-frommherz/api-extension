<?php


namespace Cs\ApiExtensionBundle\Api\Controller;

/**
 * Class ApiControllerCollection
 * @package Cs\ApiExtensionBundle\Api\Controller
 */
final class ApiControllerCollection
{
    /**
     * @var ApiControllerInterface[]
     */
    private iterable $controllers;

    /**
     * ApiControllerCollection constructor.
     * @param ApiControllerInterface[] $controllers
     */
    public function __construct(iterable $controllers)
    {
        $this->controllers = $controllers;
    }

    /**
     * @return ApiControllerInterface[]
     */
    public function getControllers(): iterable
    {
        return $this->controllers;
    }
}