<?php

namespace Jeybin\Networkintl\Facades;

use Illuminate\Support\Facades\Facade;

class NgeniusFacades extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ngenius';
    }
}