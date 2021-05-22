<?php

namespace App\Repositories;

class UserRepository
{

    public static function getStatistics()
    {
        // dd(auth()->user()->posts()
        //         ->select(
        //             array(
        //                 DB::raw('count(*) total'),
        //                 DB::raw('sum(case when status = "drafted" then 1 else 0 end) drafted'),
        //                 DB::raw('sum(case when status = "queued" then 1 else 0 end) queued'),
        //                 DB::raw('sum(case when status = "success" then 1 else 0 end) success'),
        //                 DB::raw('sum(case when status = "failed" then 1 else 0 end) failed'),
        //             )

        //         )->first());
        $posts = auth()->user()->posts()->count();
        $drafts = auth()->user()->posts()->where(['draft' => 1])->count();
        $queued = auth()->user()->posts()->where(['status' => 'pending', 'draft' => 0])->count();
        $succeeded = auth()->user()->posts()->where(['status' => 'succeeded', 'draft' => 0])->count();
        $failed = auth()->user()->posts()->where(['status' => 'failed', 'draft' => 0])->count();
        $accounts = auth()->user()->accounts()->count();

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
        return auth()->user()->accounts()->orderBy('created_at', 'desc')->get($fields);
    }

    public static function getScheduledPosts()
    {
        return auth()->user()->posts()->where(['draft' => 0, 'locked' => 0])->get();
    }

    public static function getMedia()
    {
        return auth()->user()->media()->get(['id', 'name']);
    }

    public static function getDraftedPosts()
    {
        return auth()->user()->posts()->where(['draft' => 1])->withCount(['accounts', 'media'])->orderBy('updated_at', 'desc')->paginate(16);
    }

    public static function getQueuedPosts()
    {
        return auth()->user()->posts()->where(['draft' => 0])->withCount(['accounts', 'media'])->orderBy('updated_at', 'desc')->paginate(16);
    }

    public static function getNotifications()
    {
        return auth()->user()->notifications()->orderBy('created_at', 'desc')->paginate(15);
    }

    public static function getSessions()
    {
        return auth()->user()->session()->orderBy('last_activity', 'desc')->get()->toArray();
    }

    public static function getSocialStatus()
    {
        $facebook = auth()->user()->socialAuthUser()->where(['provider' => 'facebook'])->first();
        $google = auth()->user()->socialAuthUser()->where(['provider' => 'google'])->first();

        return [
            "facebook" => $facebook,
            "google" => $google,
        ];
    }
}
