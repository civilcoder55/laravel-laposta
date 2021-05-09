<?php

namespace App\Services\Facades;

use App\Services\PostService as Service;
use Illuminate\Support\Facades\Facade;

class PostService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return app()->make(Service::class);
    }
}
