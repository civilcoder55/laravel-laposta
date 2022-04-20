<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('main.*', function ($view) {
            $notifications = cache()->rememberForever('notifications.' . auth()->user()->id, function () {
                return auth()
                    ->user()
                    ->notifications()
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
            });

            $view->with('notifications', $notifications);
        });
    }
}
