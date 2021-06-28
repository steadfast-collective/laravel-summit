<?php

namespace SteadfastCollective\ApiVideo\Facades;

use Illuminate\Support\Facades\Facade;

class ApiVideo extends Facade
{
    public static function getFacadeAccessor()
    {
        return \Laravel\Nova\Nova::class;
    }
}
