<?php


namespace Cs\ApiExtensionBundle\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

/**
 * Class ConstraintViolationException
 * @package Cs\ApiExtensionBundle\Exception
 */
class ApiConstraintViolationException extends AbstractApiException
{
    /**
     * @var ConstraintViolationListInterface
     */
    private ConstraintViolationListInterface $ConstraintViolationListInterface;

    public function __construct(ConstraintViolationListInterface $ConstraintViolationListInterface, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->ConstraintViolationListInterface = $ConstraintViolationListInterface;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getConstraintViolationListInterface(): ConstraintViolationListInterface
    {
        return $this->ConstraintViolationListInterface;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}