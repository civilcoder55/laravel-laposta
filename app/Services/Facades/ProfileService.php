<?php

namespace App\Services\Facades;

use App\Services\ProfileService as Service;
use Illuminate\Support\Facades\Facade;

class ProfileService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return app()->make(Service::class);
    }
}
