<?php


namespace Cs\ApiExtensionBundle\EventSubscriber;


use Cs\ApiExtensionBundle\Api\Response\ApiErrorResponse;
use Cs\ApiExtensionBundle\Exception\ApiExceptionInterface;
use Cs\ApiExtensionBundle\Exception\ApiEntityNotFoundException;
use Cs\ApiExtensionBundle\Exception\ApiConstraintViolationException;
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
 * Class ExceptionSubscriber
 * @package Cs\ApiExtensionBundle\EventSubscriber
 */
class ExceptionSubscriber implements EventSubscriberInterface
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
        $ex = $event->getThrowable();

        if(!$ex instanceof ApiExceptionInterface)
        {
            return;
        }

        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        $errorResponse = new ApiErrorResponse();

        if($ex instanceof ApiConstraintViolationException)
        {
            $statusCode = Response::HTTP_BAD_REQUEST;
            $this->onConstraintViolationException($ex, $errorResponse);
        }
        elseif($ex instanceof ApiEntityNotFoundException)
        {
            $statusCode = Response::HTTP_NOT_FOUND;
            $this->onGenericApiException($ex, $errorResponse);
        }
        else
        {
            $this->onGenericApiException($ex, $errorResponse);
        }

        $event->setResponse(new JsonResponse($this->normalizer->normalize($errorResponse), $statusCode));
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
        foreach ($exception->getConstraintViolationListInterface() as $violation)
        {
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