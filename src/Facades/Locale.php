<?php

namespace Bertell\Locale\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Bertell\Locale\Locale
 */
class Locale extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Bertell\Locale\Locale::class;
    }
}
