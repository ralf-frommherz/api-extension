<?php


namespace Cs\ApiExtensionBundle\Api\Request;


use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApiRequest
 * @package Cs\ApiExtensionBundle\Api
 */
abstract class ApiRequest
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * ApiRequest constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}