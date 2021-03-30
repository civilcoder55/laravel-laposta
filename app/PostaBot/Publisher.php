<?php

namespace App\PostaBot;

use Illuminate\Support\Facades\Facade;


class Publisher extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PublisherManager::class;
    }
}
