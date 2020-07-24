<?php


namespace Cs\ApiExtensionBundle\Service;


use Cs\ApiExtensionBundle\Api\Controller\ApiControllerCollection;
use Cs\ApiExtensionBundle\Api\Entity\ApiEntity;
use Cs\ApiExtensionBundle\Api\Response\ApiEntityGetResponse;
use Cs\ApiExtensionBundle\Api\Response\ApiJsonResponse;
use Cs\ApiExtensionBundle\Exception\ApiCouldNotResolveReferenceBecauseResponseIsNotInstanceOfApiEntityGetResponse;
use Cs\ApiExtensionBundle\Exception\ApiCouldNotResolveReferenceBecauseResponseIsNotInstanceOfApiJsonResponse;
use Cs\ApiExtensionBundle\Exception\ApiReferenceResolveException;
use Exception;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class InternalRequestService
 * @package Cs\ApiExtensionBundle\Service
 */
class InternalRequestService
{
    /**
     * @var RouterInterface
     */
    private RouterInterface $router;
    /**
     * @var HttpKernelInterface
     */
    private HttpKernelInterface $httpKernel;

    /**
     * InternalRequestService constructor.
     * @param RouterInterface $router
     * @param HttpKernelInterface $httpKernel
     */
    public function __construct(RouterInterface $router, HttpKernelInterface $httpKernel)
    {
        $this->router = $router;
        $this->httpKernel = $httpKernel;
    }

    /**
     * Resolves the entity by the given list of uris
     *
     * @param array $uris
     * @return array|ApiEntity[]
     */
    public function getList(array $uris)
    {
        return array_map(function (string $uri) {
            return $this->getOne($uri);
        }, $uris);
    }

    /**
     * Resolves the entity by the given uri
     *
     * @param string $uri
     * @return ApiEntity
     * @throws Exception
     */
    public function getOne(string $uri) : ApiEntity
    {
        $response = $this->executeSubRequest($uri);

        if($response->getStatusCode() !== Response::HTTP_OK)
        {
            throw new ApiReferenceResolveException('Could not resolve all relations');
        }

        if(!$response instanceof ApiJsonResponse)
        {
            throw new ApiCouldNotResolveReferenceBecauseResponseIsNotInstanceOfApiJsonResponse(sprintf(
                'Excepted "%s" got "%s"',
                ApiJsonResponse::class, get_class($response)
            ));
        }

        if(!$response->getApiResponse() instanceof ApiEntityGetResponse)
        {
            throw new ApiCouldNotResolveReferenceBecauseResponseIsNotInstanceOfApiEntityGetResponse(sprintf(
                'Excepted "%s" got "%s"',
                ApiEntityGetResponse::class, get_class($response)
            ));
        }

        /** @var ApiEntityGetResponse $apiResponse */
        $apiResponse = $response->getApiResponse();
        return $apiResponse->getEntity();
    }

    /**
     * Executes the sub request for the given uri
     *
     * @param string $uri
     * @return Response
     * @throws Exception
     */
    private function executeSubRequest(string $uri) : Response
    {
        $context = $this->router->getContext();
        $previousMethod = $context->getMethod();
        $context->setMethod(Request::METHOD_GET);
        $routeProperties = $this->router->match($uri);
        $context->setMethod($previousMethod);

        $request = new Request([], [], $routeProperties);
        return $this->httpKernel->handle(
            $request,
            HttpKernelInterface::SUB_REQUEST
        );
    }
}