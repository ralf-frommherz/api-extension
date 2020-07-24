<?php


namespace Cs\ApiExtensionBundle\EventSubscriber;


use Cs\ApiExtensionBundle\Api\Response\ApiErrorResponse;
use Cs\ApiExtensionBundle\Exception\ApiExceptionInterface;
use Cs\ApiExtensionBundle\Exception\ApiConstraintViolationException;
use Cs\ApiExtensionBundle\Service\ApiRouteService;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Throwable;

/**
 * Deals with api exceptions
 *
 * Class ApiExceptionSubscriber
 * @package Cs\ApiExtensionBundle\EventSubscriber
 */
class ApiExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var NormalizerInterface
     */
    private NormalizerInterface $normalizer;

    /**
     * ExceptionSubscriber constructor.
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
            KernelEvents::EXCEPTION => 'onException'
        ];
    }

    /**
     * @param ExceptionEvent $event
     * @throws ExceptionInterface
     * @throws ReflectionException
     */
    public function onException(ExceptionEvent $event): void
    {
        $request = $event->getRequest();
        if ($request->attributes->get('routeType') !== ApiRouteService::ROUTE_TYPE_NAME) {
            return;
        }

        $ex = $event->getThrowable();
        $errorResponse = new ApiErrorResponse();

        if ($ex instanceof ApiConstraintViolationException) {
            $this->onConstraintViolationException($ex, $errorResponse);
        } else {
            $this->onGenericApiException($ex, $errorResponse);
        }

        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        if($ex instanceof ApiExceptionInterface)
        {
            $statusCode = $ex->getStatusCode();
        }

        $event->setResponse(new JsonResponse(
            $this->normalizer->normalize($errorResponse),
            $statusCode
        ));
    }

    /**
     * Handles the constraint violation exception
     *
     * @param ApiConstraintViolationException $exception
     * @param ApiErrorResponse $errorResponse
     * @throws ReflectionException
     */
    public function onConstraintViolationException(ApiConstraintViolationException $exception, ApiErrorResponse $errorResponse): void
    {
        /** @var ConstraintViolation $violation */
        foreach ($exception->getConstraintViolationListInterface() as $violation) {
            $errorResponse->addPropertyError(
                $this->getExceptionShortName($exception),
                $violation->getMessage(),
                $violation->getPropertyPath()
            );
        }
    }

    /**
     * @param Throwable $exception
     * @param ApiErrorResponse $errorResponse
     * @throws ReflectionException
     */
    public function onGenericApiException(Throwable $exception, ApiErrorResponse $errorResponse): void
    {
        $errorResponse->addError(
            $this->getExceptionShortName($exception),
            $exception->getMessage()
        );
    }

    /**
     * @param Throwable $throwable
     * @return string
     * @throws ReflectionException
     */
    private function getExceptionShortName(Throwable $throwable): string
    {
        return (new ReflectionClass($throwable))->getShortName();
    }
}