<?php

namespace App\Repositories;

class UserRepository
{

    public static function getStatistics()
    {

        $posts = auth()->user()->posts()->selectRaw("count(*) AS total,
        SUM(IF(draft = 1 , 1, 0)) AS drafts,
        SUM(IF(draft = 0 AND status='pending' , 1, 0)) AS queued,
        SUM(IF(draft = 0 AND status='succeeded', 1, 0)) AS succeeded,
        SUM(IF(draft = 1 AND status='failed', 1, 0)) AS failed"
        )->first();
        $accounts = auth()->user()->accounts()->count();

        return [
            'posts' => $posts->total,
            'drafts' => $posts->drafts ?? 0,
            'queued' => $posts->queued ?? 0,
            'succeeded' => $posts->succeeded ?? 0,
            'failed' => $posts->failed ?? 0,
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

    public static function getMedia($fields = "*")
    {
        return auth()->user()->media()->get($fields);
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
