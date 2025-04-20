<?php

namespace Ganyicz\VoltNamespace\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ganyicz\VoltNamespace\VoltNamespace
 */
class VoltNamespace extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Ganyicz\VoltNamespace\VoltNamespace::class;
    }
}
