<?php

namespace Chantouch\Addresses\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Address
 * @package Chantouch\Addresses\Facades
 */
class Address extends Facade
{
    /** @inheritdoc */
    protected static function getFacadeAccessor(): string
    {
        return 'address';
    }
}