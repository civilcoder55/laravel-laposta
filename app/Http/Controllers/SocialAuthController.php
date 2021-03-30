<?php

namespace App\Http\Controllers;

use App\Models\SocialAuthUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    //

    public function redirectToDrive($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    public function redirectionHandler($driver)
    {
        try {
            $data = Socialite::driver($driver)->user();
            return $this->userHandler($data, $driver);
        } catch (\Exception $e) {
            return redirect(route('login'))->with('status', "{$driver} Login failed");
        }
    }

    public function userHandler($data, $provider)
    {
        $socialUser = SocialAuthUser::where([['provider', $provider], ['uid', $data->id]])->first();
        // login user with his linked social account
        if ($socialUser && !Auth::check()) {
            Auth::login($socialUser->user, true);
            return redirect('/');
        }

        // redirect if user already logged in
        if ($socialUser && Auth::check()) {
            return redirect('/profile');
        }

        // link existing user with  social account
        if (!$socialUser && Auth::check()) {
            auth()->user()->socialAuthUser()->create(['provider' => $provider, 'uid' => $data->id, 'token' => $data->token]);
            return redirect('/profile');
        }

        //register with social account
        if (!$socialUser && !Auth::check() && !User::where(['email' => $data->email])->first()) {
            $user = User::create(['email' => $data->email, 'name' => $data->name, 'password' => Hash::make(Str::random(24))]);
            $user->socialAuthUser()->create(['provider' => $provider, 'uid' => $data->id, 'token' => $data->token]);
            Auth::login($user, true);
            return redirect('/');
        }

        return redirect('/login')->with('status', 'Error happend or email conflict');
    }

    public function revokeFacebook()
    {

        if ($socialUser = auth()
            ->user()
            ->socialAuthUser()
            ->where(['provider' => 'facebook'])
            ->first()) {
            $token = $socialUser->token;
            $response = Http::delete("https://graph.facebook.com/v2.4/me/permissions?access_token={$token}");
            if ($response->ok()) {
                $socialUser->delete();
            }

        }

        return redirect('/profile');
    }

    public function revokeGoogle()
    {

        if ($socialUser = auth()
            ->user()
            ->socialAuthUser()
            ->where(['provider' => 'google'])
            ->first()) {
            $token = $socialUser->token;
            $response = Http::withHeaders([
                'Content-type' => 'application/x-www-form-urlencoded',
            ])->post("https://oauth2.googleapis.com/revoke?token={$token}");
            if ($response->ok()) {
                $socialUser->delete();
            }
        }

        return redirect('/profile');
    }
}
