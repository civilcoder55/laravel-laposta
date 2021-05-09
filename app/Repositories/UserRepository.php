<?php

namespace App\Repositories;

class UserRepository
{

    private $user;
    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function getStatistics()
    {
        // dd($this->user->posts()
        //         ->select(
        //             array(
        //                 DB::raw('count(*) total'),
        //                 DB::raw('sum(case when status = "drafted" then 1 else 0 end) drafted'),
        //                 DB::raw('sum(case when status = "queued" then 1 else 0 end) queued'),
        //                 DB::raw('sum(case when status = "success" then 1 else 0 end) success'),
        //                 DB::raw('sum(case when status = "failed" then 1 else 0 end) failed'),
        //             )

        //         )->first());
        $total = $this->user->posts()->count();
        $queued = $this->user->posts()->where(['status' => 'queued'])->count();
        $success = $this->user->posts()->where(['status' => 'success'])->count();
        $failed = $this->user->posts()->where(['status' => 'failed'])->count();
        $accounts = $this->user->accounts()->count();

        return [
            'total' => $total,
            'queued' => $queued,
            'success' => $success,
            'failed' => $failed,
            'accounts' => $accounts,
        ];
    }
    public function getAccounts($fields = "*")
    {
        return $this->user->accounts()->orderBy('created_at', 'desc')->get($fields);
    }

    public function getScheduledPosts()
    {
        return $this->user->posts()->where(['is_draft' => 0, 'success' => null])->get();
    }

    public function getMedia()
    {
        return $this->user->media()->get(['id', 'name']);
    }

    public function getPosts()
    {
        return $this->user->posts()->with(['media:name'])->withCount(['accounts'])->orderBy('updated_at', 'desc')->paginate(16);
    }

    public function getNotifications()
    {
        return $this->user->notifications()->orderBy('created_at', 'desc')->paginate(15);
    }

    public function getSessions()
    {
        return $this->user->session()->orderBy('last_activity', 'desc')->get()->toArray();
    }

    public function getSocialStatus()
    {
        $facebook = $this->user->socialAuthUser()->where(['provider' => 'facebook'])->first();
        $google = $this->user->socialAuthUser()->where(['provider' => 'google'])->first();

        return [
            "facebook" => $facebook,
            "google" => $google,
        ];
    }
}
