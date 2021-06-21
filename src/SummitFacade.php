<?php

namespace SteadfastCollective\Summit;

use Illuminate\Support\Facades\Facade;

class SummitFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'summit';
    }
}
