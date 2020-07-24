<?php


namespace Cs\ApiExtensionBundle\Api\Resolver;

use Cs\ApiExtensionBundle\Api\Entity\ApiEntity;
use Cs\ApiExtensionBundle\Exception\ApiBadRequestException;
use Cs\ApiExtensionBundle\Exception\ApiConstraintViolationException;
use Cs\ApiExtensionBundle\Exception\ApiNotInstanceOfApiEntityException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ApiRequestResolver
 * @package Cs\ApiExtensionBundle\Api\Request
 */
abstract class ApiRequestResolver
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * ApiRequestResolver constructor.
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * Creates the api entity by the request
     *
     * @param Request $request
     * @return ApiEntity
     */
    protected function getApiEntityByRequest(Request $request) : ApiEntity
    {
        $content = $request->getContent();
        $entityFqn = $request->attributes->get('entity');
        $apiEntity = $this->serializer->deserialize($content, $entityFqn, 'json');

        if(!$apiEntity instanceof ApiEntity)
        {
            throw new ApiNotInstanceOfApiEntityException(sprintf(
                'The given entity "%s" is not instance of "%s"',
                get_class($apiEntity), ApiEntity::class
            ));
        }

        if($id = $request->get('id'))
        {
            $apiEntity->setId($id);
        }

        $errors = $this->validator->validate($apiEntity);

        if(count($errors) > 0)
        {
            throw new ApiConstraintViolationException($errors);
        }

        return $apiEntity;
    }

    /**
     * Extracts the id out of the request object.
     *
     * @param Request $request
     * @return string
     */
    protected function getIdByRequest(Request $request) : string
    {
        $id = $request->get('id');

        if(empty($id))
        {
            throw new ApiBadRequestException('Invalid id given');
        }

        return $id;
    }
}