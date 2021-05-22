<?php

namespace App\Http\Controllers;

use App\Services\SocialAuthService;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{

    public function connect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $data = Socialite::driver($provider)->user();
        return SocialAuthService::handleUser($data, $provider);
    }

    public function disconnectFacebook()
    {
        $socialUser = auth()->user()->socialAuthUser()->where(['provider' => 'facebook'])->firstOrFail();
        $socialUser->delete();
        SocialAuthService::revokeFacebookToken($socialUser->token);
        return redirect()->route('profile.index');
    }

    public function disconnectGoogle()
    {
        $socialUser = auth()->user()->socialAuthUser()->where(['provider' => 'google'])->firstOrFail();
        $socialUser->delete();
        SocialAuthService::revokeGoogleToken($socialUser->token);
        return redirect()->route('profile.index');
    }
}
