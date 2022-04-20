<?php

namespace App\Services;

use Carbon\Carbon;

class PostService
{

    public static function store($request)
    {
        $log = self::storeLog($request);
        $post = auth()->user()->posts()->create(['draft' => (bool) $request->draft, 'message' => $request->message, 'schedule_date' => $request->schedule_date, 'logs' => $log]);
        $post->media()->attach($request->media);
        $post->accounts()->attach($request->accounts);
    }
    public static function update($request, $post)
    {
        $post->draft = (bool) $request->draft ? 1 : 0;
        $post->message = $request->message;
        $post->schedule_date = $request->schedule_date;
        $mediaSync = $post->media()->sync($request->media);
        $accountsSync = $post->accounts()->sync($request->accounts);
        if ($post->isDirty() || ($mediaSync['attached'] || $mediaSync['detached']) || ($accountsSync['attached'] || $accountsSync['detached'])) {
            $log = static::updateLog($request);
            $post->logs = $log;
            $post->save();
        }
    }
    private static function storeLog($request)
    {
        $now = Carbon::now()->toDateTimeString();
        $then = Carbon::createFromTimestamp($request->schedule_date)->toDateTimeString();
        if ($request->draft) {
            $message = "Post saved as draft at {$now}";
        } else {
            $message = "Post saved and scheduled at {$now} to be published at {$then}";
        }
        return [['status' => 'info', 'message' => $message]];
    }

    private static function updateLog($request)
    {
        $now = Carbon::now()->toDateTimeString();
        $then = Carbon::createFromTimestamp($request->schedule_date)->toDateTimeString();
        if ($request->draft) {
            $message = "Post updated and saved as draft at {$now}";
        } else {
            $message = "Post updated and scheduled at {$now} to be published at {$then}";
        }
        return [['status' => 'info', 'message' => $message]];
    }
}
