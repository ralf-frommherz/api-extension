<?php


namespace Cs\ApiExtensionBundle\Routing;

use Cs\ApiExtensionBundle\Service\ApiRouteService;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Registers the api controller routes
 *
 * Class ApiLoader
 * @package Cs\ApiExtensionBundle\Routing
 */
class ApiLoader extends Loader
{
    /**
     * @var ApiRouteService
     */
    private ApiRouteService $apiRouteService;

    /**
     * ApiLoader constructor.
     * @param ApiRouteService $apiRouteService
     */
    public function __construct(ApiRouteService $apiRouteService)
    {
        $this->apiRouteService = $apiRouteService;
    }

    /**
     * @inheritDoc
     */
    public function supports($resource, string $type = null)
    {
        return ApiRouteService::ROUTE_TYPE_NAME === $type;
    }

    /**
     * @inheritDoc
     */
    public function load($resource, string $type = null)
    {
        $routes = new RouteCollection();

        foreach ($this->apiRouteService->getApiRoutes() as $apiRoute) {
            $routes->add($apiRoute['routeName'], new Route(
                $apiRoute['path'],
                $apiRoute['defaults'],
                [], [], '', [],
                $apiRoute['methods']
            ));
        }

        return $routes;
    }
}