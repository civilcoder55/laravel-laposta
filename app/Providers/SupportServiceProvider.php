<?php

namespace App\Providers;

use App\Repositories\UserRepository;
use App\Services\MediaService;
use App\Services\PostService;
use App\Services\ProfileService;
use App\Services\SocialAuthService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public $singletons = [
        UserRepository::class => UserRepository::class,
        MediaService::class => MediaService::class,
        PostService::class => PostService::class,
        ProfileService::class => ProfileService::class,
        SocialAuthService::class => SocialAuthService::class,
    ];

    public function provides()
    {
        return [UserRepository::class, MediaService::class, PostService::class, ProfileService::class, SocialAuthService::class];
    }
}
