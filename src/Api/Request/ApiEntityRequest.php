<?php


namespace Cs\ApiExtensionBundle\Api\Request;


use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApiEntityRequest
 * @package Cs\ApiExtensionBundle\Api\Request
 */
abstract class ApiEntityRequest extends ApiRequest
{
    /**
     * @var string
     */
    private string $id;

    /**
     * ItemRequest constructor.
     * @param string $id
     * @param Request $request
     */
    public function __construct(string $id, Request $request)
    {
        parent::__construct($request);
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}