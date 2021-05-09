<?php

namespace App\Providers;

use App\PostaBot\Contracts\Tokenizable;
use App\PostaBot\PublisherManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PostaBotServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Tokenizable::class, function ($app) {
            $platforms = ['facebook' => 'App\PostaBot\TokenizerProviders\Facebook', 'twitter' => 'App\PostaBot\TokenizerProviders\Twitter', 'instagram' => 'App\PostaBot\TokenizerProviders\Instagram'];
            $platform = Route::current()->parameter('platform');
            return new $platforms[$platform];

        });

        $this->app->singleton(PublisherManager::class, function ($app) {
            return new PublisherManager($app);
        });

    }
}
