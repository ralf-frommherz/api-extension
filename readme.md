![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/corey-mac/api-extension)
![GitHub issues](https://img.shields.io/github/issues/corey-mac/api-extension)
![Packagist License](https://img.shields.io/packagist/l/corey-mac/api-extension)
![GitHub last commit](https://img.shields.io/github/last-commit/corey-mac/api-extension)

# Api extension
Api extension allows you to easy create custom api endpoints

## usage
### api entity
The api entity represents your entity that you want to expose. You just have to create a regular class that extends the
ApiEntity.

```php
<?php

namespace App\Api\Task;

use Cs\ApiExtensionBundle\Api\Entity\ApiEntity;
use Cs\ApiExtensionBundle\Api\Entity\Traits\IdAwareApiEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TaskEntity
 * @package App\Api\Entity
 */
class TaskEntity extends ApiEntity
{
    use IdAwareApiEntity;

    /**
     * @Assert\NotBlank()
     * @var string
     */
    protected string $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
```

### reference annotations
If you want to use references, you can use the reference annotation. Now api extension will accept and provide uris 
instead of the actual object.

#### example
```php
use Cs\ApiExtensionBundle\Api\Entity\Annotations\ReferenceMany;
use Cs\ApiExtensionBundle\Api\Entity\Annotations\ReferenceOne;

class ... extends ApiEntity
{
    /**
     * @ReferenceOne(type=SomeEntity::class)
     * @var SomeEntity
     */
    protected SomeEntity $someEntity;
```

### IdAwareApiEntity
The IdAwareApiEntity trait provides an id field in your entity that is automatically used in your requests.

#### example
```php
use Cs\ApiExtensionBundle\Api\Entity\Traits\IdAwareApiEntity;

class ... extends ApiEntity
{
    use IdAwareApiEntity;
```

## api entity collection
An entity collection contains multiple api entity instances

```php
namespace App\Api\Connection;

use Cs\ApiExtensionBundle\Api\Entity\ApiEntityCollection;

/**
 * Class ConnectionEntityCollection
 * @package App\Api\Entity
 */
class ConnectionEntityCollection extends ApiEntityCollection
{
    /**
     * @inheritDoc
     */
    public function getEntityClassName(): string
    {
        return ConnectionEntity::class;
    }
}
```

## api controller
The api controller is a regular symfony controller with some extras.

### ApiControllerInterface interface
To make api extension know your controllers (this is used to register the routes), you need to implement the marker 
interface ApiControllerInterface.

### operation annotations
Operation annotations tell the api what operation your action is for. This information will be used to generate routes.

*  @Annotations\Operation(type="collection-create")
*  @Annotations\Operation(type="collection-get")
*  @Annotations\Operation(type="item-get")
*  @Annotations\Operation(type="item-delete")
*  @Annotations\Operation(type="item-patch")

### example

```php
<?php


namespace App\Api\Task;

use App\Api\Connection\ConnectionConverter;
use Cs\ApiExtensionBundle\Api\Controller\ApiControllerInterface;
use Cs\ApiExtensionBundle\Api\Controller\Annotations;
use Cs\ApiExtensionBundle\Api\Request\ApiEntityDeleteRequest;
use Cs\ApiExtensionBundle\Api\Request\ApiEntityGetRequest;
use Cs\ApiExtensionBundle\Api\Response\ApiCollectionPostResponse;
use Cs\ApiExtensionBundle\Api\Response\ApiCollectionResponse;
use Cs\ApiExtensionBundle\Api\Response\ApiEntityDeleteResponse;
use Cs\ApiExtensionBundle\Api\Response\ApiEntityGetResponse;
use Cs\ApiExtensionBundle\Api\Response\ApiEntityUpdateResponse;
use Cs\ApiExtensionBundle\Exception\ApiEntityNotFoundException;
use Cs\ApiExtensionBundle\Traits\ExceptionAwareApiController;

/**
 * Class TaskController
 * @package App\Api\Task
 * @Annotations\ApiController(entity=TaskEntity::class)
 */
class TaskController implements ApiControllerInterface
{
    use ExceptionAwareApiController;

    /**
     * @Annotations\Operation(type="collection-create")
     */
    public function create(TaskEntity $taskEntity): ApiCollectionPostResponse
    {
        return new ApiCollectionPostResponse($taskEntity);
    }

    /**
     * @Annotations\Operation(type="collection-get")
     * @return ApiCollectionResponse
     */
    public function getAll(): ApiCollectionResponse
    {
        $taskEntities = []; // some service that loads all tasks
        $totalEntityCount = 0; // total count of all entities
        return new ApiCollectionResponse(new TaskEntityCollection($taskEntities, $totalEntityCount));
    }

    /**
     * @Annotations\Operation(type="item-get")
     * @param ApiEntityGetRequest $request
     * @return ApiEntityGetResponse
     */
    public function get(ApiEntityGetRequest $request): ApiEntityGetResponse
    {
        $taskEntity = $this->someService->get($request->getId());
        $this->throwApiResponseEntityNotFoundExceptionIfNull($request->getId(), $task);
        return new ApiEntityGetResponse($this->convertTaskToApiEntity($task));
    }

    /**
     * @Annotations\Operation(type="item-delete")
     * @param ApiEntityDeleteRequest $request
     * @return ApiEntityDeleteResponse
     */
    public function delete(ApiEntityDeleteRequest $request): ApiEntityDeleteResponse
    {
        $success = $this->someService->delete($request->getId());
        
        if(!$success)
        {
            $this->throwApiResponseEntityNotFoundException($request->getId());
        }

        return new ApiEntityDeleteResponse();
    }

    /**
     * @Annotations\Operation(type="item-patch")
     * @param TaskEntity $taskEntity
     * @return ApiEntityUpdateResponse
     */
    public function update(TaskEntity $taskEntity): ApiEntityUpdateResponse
    {
        ...
        return new ApiEntityUpdateResponse();
    }
}
```