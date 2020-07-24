<?php


namespace Cs\ApiExtensionBundle\EventSubscriber;


use Cs\ApiExtensionBundle\Api\Response\ApiEntityDeleteResponse;
use Cs\ApiExtensionBundle\Api\Response\ApiEntityUpdateResponse;
use Cs\ApiExtensionBundle\Api\Response\ApiJsonResponse;
use Cs\ApiExtensionBundle\Api\Response\ApiResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Serializes the api response objects
 *
 * Class ApiResponseSubscriber
 * @package Cs\ApiExtensionBundle\EventSubscriber
 */
class ApiResponseSubscriber implements EventSubscriberInterface
{
    /**
     * @var NormalizerInterface
     */
    private NormalizerInterface $normalizer;

    /**
     * ApiResponseSubscriber constructor.
     * @param NormalizerInterface $normalizer
     */
    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    /**
     * Replaces the empty response with the api response data
     *
     * @param ResponseEvent $event
     * @throws ExceptionInterface
     */
    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        if(!$response instanceof ApiResponse)
        {
            return;
        }

        if($response instanceof ApiEntityDeleteResponse || $response instanceof ApiEntityUpdateResponse)
        {
            $event->setResponse(new JsonResponse([]));
            return;
        }

        $normalizedResponse = $this->normalizer->normalize($response);
        $event->setResponse(new ApiJsonResponse($response, $normalizedResponse));
    }
}