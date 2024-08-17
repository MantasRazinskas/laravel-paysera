<?php

namespace Rpagency\LaravelPaysera\Facades;

use Illuminate\Support\Facades\Facade;

class Paysera extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'paysera';
    }
}
