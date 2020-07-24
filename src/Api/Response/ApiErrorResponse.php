<?php


namespace Cs\ApiExtensionBundle\Api\Response;

/**
 * Class ErrorResponse
 * @package Cs\ApiExtensionBundle\Api\Response
 */
class ApiErrorResponse
{
    /**
     * @var array
     */
    private array $errors = [];

    /**
     * @param string $type
     * @param string $message
     */
    public function addError(string $type, string $message): void
    {
        $this->errors[] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * @param string $type
     * @param string $message
     * @param string $propertyName
     */
    public function addPropertyError(string $type, string $message, string $propertyName) : void
    {
        $this->errors[] = [
            'type' => $type,
            'message' => $message,
            'property' => $propertyName
        ];
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}