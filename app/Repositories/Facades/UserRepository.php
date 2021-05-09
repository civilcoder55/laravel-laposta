<?php

namespace App\Repositories\Facades;

use App\Repositories\UserRepository as Repository;
use Illuminate\Support\Facades\Facade;

class UserRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return app()->make(Repository::class);
    }
}
