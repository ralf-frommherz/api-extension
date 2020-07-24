<?php


namespace Cs\ApiExtensionBundle\Api;

/**
 * Class Operations
 * @package Cs\ApiExtensionBundle\Api
 */
abstract class Operations
{
    public const OPERATION_COLLECTION_POST = 'collection-create';
    public const OPERATION_COLLECTION_GET = 'collection-get';
    public const OPERATION_ITEM_GET = 'item-get';
    public const OPERATION_ITEM_DELETE = 'item-delete';
    public const OPERATION_ITEM_PATCH = 'item-patch';
}