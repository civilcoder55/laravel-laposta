<?php

namespace App\PostaBot\TokenizerProviders;

use App\PostaBot\Contracts\Tokenizable;
use Laravel\Socialite\Facades\Socialite;

class Twitter implements Tokenizable
{
    public function redirect()
    {
        return Socialite::driver('twitter')->redirect();
    }

    public function getAndSaveData()
    {
        $user = Socialite::driver('twitter')->user();
        auth()->user()->accounts()->updateOrCreate(
            ['platform' => 'Twitter', 'uid' => $user->id],
            ['name' => $user->nickname, 'type' => 'Account', 'token' => $user->token, 'secret' => $user->tokenSecret]
        );
    }

    public function revoke($accessToken)
    {
    }
}
