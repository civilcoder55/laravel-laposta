<?php

namespace App\PostaBot;

use Exception;
use Illuminate\Support\Manager;

class PublisherManager extends Manager
{

    public function getDefaultDriver()
    {
        throw new Exception('invalid driver');
    }

    public function createFacebookDriver()
    {
        return new \App\PostaBot\PublisherProviders\Facebook();
    }

    public function createTwitterDriver()
    {
        return new \App\PostaBot\PublisherProviders\Twitter();
    }

}
