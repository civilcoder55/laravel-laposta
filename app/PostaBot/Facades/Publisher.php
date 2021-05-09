<?php

namespace App\PostaBot\Facades;

use App\PostaBot\PublisherManager;
use Illuminate\Support\Facades\Facade;

// facade to shorthand PublisherManager name and access its methods as static
class Publisher extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PublisherManager::class;
    }
}
