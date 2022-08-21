<?php

namespace Sagordev\PowersmsGateway\Facades;

use Illuminate\Support\Facades\Facade;

class PowerSms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'powersms';
    }
}