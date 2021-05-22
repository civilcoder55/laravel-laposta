<?php

namespace App\Repositories;

class UserRepository
{

    private static $user= auth()->user();
    public static function getStatistics()
    {
        // dd(self::$user->posts()
        //         ->select(
        //             array(
        //                 DB::raw('count(*) total'),
        //                 DB::raw('sum(case when status = "drafted" then 1 else 0 end) drafted'),
        //                 DB::raw('sum(case when status = "queued" then 1 else 0 end) queued'),
        //                 DB::raw('sum(case when status = "success" then 1 else 0 end) success'),
        //                 DB::raw('sum(case when status = "failed" then 1 else 0 end) failed'),
        //             )

        //         )->first());
        $posts = self::$user->posts()->count();
        $drafts = self::$user->posts()->where(['draft' => 1])->count();
        $queued = self::$user->posts()->where(['status' => 'pending', 'draft' => 0])->count();
        $succeeded = self::$user->posts()->where(['status' => 'succeeded', 'draft' => 0])->count();
        $failed = self::$user->posts()->where(['status' => 'failed', 'draft' => 0])->count();
        $accounts = self::$user->accounts()->count();

        return [
            'posts' => $posts,
            'drafts' => $drafts,
            'queued' => $queued,
            'succeeded' => $succeeded,
            'failed' => $failed,
            'accounts' => $accounts,
        ];
    }
    public static function getAccounts($fields = "*")
    {
        return self::$user->accounts()->orderBy('created_at', 'desc')->get($fields);
    }

    public static function getScheduledPosts()
    {
        return self::$user->posts()->where(['draft' => 0, 'locked' => 0])->get();
    }

    public static function getMedia()
    {
        return self::$user->media()->get(['id', 'name']);
    }

    public static function getDraftedPosts()
    {
        return self::$user->posts()->where(['draft' => 1])->withCount(['accounts', 'media'])->orderBy('updated_at', 'desc')->paginate(16);
    }

    public static function getQueuedPosts()
    {
        return self::$user->posts()->where(['draft' => 0])->withCount(['accounts', 'media'])->orderBy('updated_at', 'desc')->paginate(16);
    }

    public static function getNotifications()
    {
        return self::$user->notifications()->orderBy('created_at', 'desc')->paginate(15);
    }

    public static function getSessions()
    {
        return self::$user->session()->orderBy('last_activity', 'desc')->get()->toArray();
    }

    public static function getSocialStatus()
    {
        $facebook = self::$user->socialAuthUser()->where(['provider' => 'facebook'])->first();
        $google = self::$user->socialAuthUser()->where(['provider' => 'google'])->first();

        return [
            "facebook" => $facebook,
            "google" => $google,
        ];
    }
}
