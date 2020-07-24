<?php


namespace Cs\ApiExtensionBundle\Service;


use Cs\ApiExtensionBundle\Api\Controller\Annotations\ApiController;
use Cs\ApiExtensionBundle\Api\Controller\Annotations\Operation;
use Cs\ApiExtensionBundle\Api\Controller\ApiControllerCollection;
use Cs\ApiExtensionBundle\Api\Entity\ApiEntity;
use Cs\ApiExtensionBundle\Api\Operations;
use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;

class ApiRouteService
{
    public const ROUTE_TYPE_NAME = 'api';
    public const ROUTE_PREFIX = 'api/';
    /**
     * Operations that require an /{id} param
     */
    private const OPERATIONS_WITH_ID = [
        Operations::OPERATION_ITEM_GET,
        Operations::OPERATION_ITEM_PATCH,
        Operations::OPERATION_ITEM_DELETE
    ];
    /**
     * Mapping between operation and http method
     */
    private const OPERATION_METHOD_MAPPING = [
        Operations::OPERATION_COLLECTION_POST => Request::METHOD_POST,
        Operations::OPERATION_COLLECTION_GET => Request::METHOD_GET,
        Operations::OPERATION_ITEM_GET => Request::METHOD_GET,
        Operations::OPERATION_ITEM_PATCH => Request::METHOD_PATCH,
        Operations::OPERATION_ITEM_DELETE => Request::METHOD_DELETE,
    ];


    /**
     * @var ApiControllerCollection
     */
    private ApiControllerCollection $apiControllerCollection;
    /**
     * @var Reader
     */
    private Reader $reader;
    /**
     * @var CacheInterface
     */
    private CacheInterface $cache;

    /**
     * ApiRouteService constructor.
     * @param ApiControllerCollection $apiControllerCollection
     * @param Reader $reader
     * @param CacheInterface $cache
     */
    public function __construct(ApiControllerCollection $apiControllerCollection, Reader $reader, CacheInterface $cache)
    {
        $this->apiControllerCollection = $apiControllerCollection;
        $this->reader = $reader;
        $this->cache = $cache;
    }

    /**
     * Creates a list of routes by the present api controllers
     *
     * @return array
     * @throws ReflectionException
     */
    public function getApiRoutes(): array
    {
        $routes = [];

        foreach ($this->apiControllerCollection->getControllers() as $apiController)
        {
            $reflectionClass = new ReflectionClass($apiController);
            /** @var ApiController $apiControllerAnnotation */
            $apiControllerAnnotation = $this->reader->getClassAnnotation($reflectionClass, ApiController::class);
            $resourceName = $apiControllerAnnotation->getName() ?? $this->createResourceNameByEntityName($apiControllerAnnotation->getEntity());

            foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $reflectionMethod)
            {
                /** @var Operation $operationAnnotation */
                $operationAnnotation = $this->reader->getMethodAnnotation($reflectionMethod, Operation::class);

                // no api relevant method
                if(!$operationAnnotation)
                {
                    continue;
                }

                $routes[] = [
                    'routeName' => 'api_'.$resourceName.'_'.$operationAnnotation->getType(),
                    'path' => $this->getPath($resourceName, $operationAnnotation->getType()),
                    'defaults' => [
                        '_controller' => $reflectionClass->getName().'::'.$reflectionMethod->getName(),
                        'resource' => $resourceName,
                        'entity' => $apiControllerAnnotation->getEntity(),
                        'operation' => $operationAnnotation->getType(),
                        'routeType' => self::ROUTE_TYPE_NAME
                    ],
                    'methods' => [self::OPERATION_METHOD_MAPPING[$operationAnnotation->getType()]]
                ];
            }
        }

        return $routes;
    }

    /**
     * Creates the resource name by the entity name
     *
     * @param string $entityFqn
     * @return string
     * @throws ReflectionException
     */
    private function createResourceNameByEntityName(string $entityFqn): string
    {
        $shortName = (new ReflectionClass($entityFqn))->getShortName();
        return strtolower(str_replace('Entity', '', $shortName));
    }

    /**
     * Creates a path for a specific resource name and operation
     *
     * @param string $resourceName
     * @param string $type
     * @return string
     */
    public function getPath(string $resourceName, string $type): string
    {
        $path = self::ROUTE_PREFIX.$resourceName;
        if(in_array($type, self::OPERATIONS_WITH_ID, true))
        {
            $path .= '/{id}';
        }
        return '/'.$path;
    }

    /**
     * Loads the path for the get method by an given api entity
     *
     * @param ApiEntity $apiEntity
     * @param string $operation
     * @return string|null
     * @throws ReflectionException
     */
    public function getPathByEntity(ApiEntity $apiEntity, string $operation = Operations::OPERATION_ITEM_GET): ?string
    {
        $route = $this->getRouteByEntity($apiEntity, $operation);

        if(!$route)
        {
            return null;
        }

        return str_replace('{id}', urlencode($apiEntity->getId()), $route['path']);
    }

    /**
     * Resolves all paths by the given entities
     * @param ApiEntity[] $entities
     * @return array
     */
    public function getPathsByEntity(array $entities) : array
    {
        return array_map(function (ApiEntity $entity) {
            return $this->getPathByEntity($entity);
        }, $entities);
    }

    /**
     * @param ApiEntity $apiEntity
     * @return mixed|null
     * @throws ReflectionException
     */
    private function getRouteByEntity(ApiEntity $apiEntity, string $operation) : ?array
    {
        $className = get_class($apiEntity);
        $cacheKey = md5('getRouteByEntity_'.$operation.'_'.$className);

        return $this->cache->get($cacheKey, function () use ($className, $operation) {
            foreach ($this->getApiRoutes() as $apiRoute)
            {
                if(
                    $apiRoute['defaults']['entity'] === $className &&
                    $apiRoute['defaults']['operation'] === $operation)
                {
                    return $apiRoute;
                }
            }

            return null;
        });
    }
}