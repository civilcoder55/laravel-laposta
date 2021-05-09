<?php

namespace App\Services\Facades;

use App\Services\MediaService as Service;
use Illuminate\Support\Facades\Facade;

class MediaService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return app()->make(Service::class);
    }
}
