<?php

namespace App\Services;

use App\Models\SocialAuthUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SocialAuthService
{

    public static function handleUser($data, $provider)
    {
        $socialUser = SocialAuthUser::where([['provider', $provider], ['uid', $data->id]])->first();
        $loggedIn = Auth::check();
        // login user with his linked social account
        if ($socialUser && !$loggedIn) {
            Auth::login($socialUser->user);
            return redirect()->route('dashboard');
        }

        // redirect if user already logged in
        if ($socialUser && $loggedIn) {
            return redirect()->route('profile.index');
        }

        // link existing user with  social account
        if (!$socialUser && $loggedIn) {
            $alreadyExist = User::where(['email' => $data->email])->first();
            if ($alreadyExist) {
                return redirect()->route('profile.index')->with('status', 'already linked to another laposta account');
            }
            auth()->user()->socialAuthUser()->create(['provider' => $provider, 'uid' => $data->id, 'token' => $data->token]);
            return redirect()->route('profile.index');
        }

        //register with social account

        if (!$socialUser && !$loggedIn) {
            $alreadyExist = User::where(['email' => $data->email])->first();
            $user = $alreadyExist ? $alreadyExist : User::create(['email' => $data->email, 'name' => $data->name, 'password' => Hash::make(Str::random(24))]);
            $user->socialAuthUser()->create(['provider' => $provider, 'uid' => $data->id, 'token' => $data->token]);
            Auth::login($user);
            return redirect()->route('dashboard');
        }

        return redirect()->route('login')->with('status', 'Error happend ');
    }

    public static function revokeFacebookToken($token)
    {
        $response = Http::delete("https://graph.facebook.com/v2.4/me/permissions?access_token={$token}");
    }

    public static function revokeGoogleToken($token)
    {
        $response = Http::withHeaders([
            'Content-type' => 'application/x-www-form-urlencoded',
        ])->post("https://oauth2.googleapis.com/revoke?token={$token}");
    }

}
