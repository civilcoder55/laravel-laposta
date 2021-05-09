<?php

namespace App\Services\Facades;

use App\Services\SocialAuthService as Service;
use Illuminate\Support\Facades\Facade;

class SocialAuthService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return app()->make(Service::class);
    }
}
