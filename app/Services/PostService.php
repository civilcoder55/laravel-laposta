<?php

namespace App\Services;

use Carbon\Carbon;

class PostService
{

    public function store($request)
    {
        $log = $this->storeLog($request);
        $post = auth()->user()->posts()->create(['draft' => (bool) $request->draft, 'message' => $request->message, 'schedule_date' => $request->schedule_date, 'logs' => $log]);
        $post->media()->attach($request->media);
        $post->accounts()->attach($request->accounts);
    }
    public function update($request, $post)
    {
        $post->update(['draft' => (bool) $request->draft, 'message' => $request->message, 'schedule_date' => $request->schedule_date]);
        $mediaSync = $post->media()->sync($request->media);
        $accountsSync = $post->accounts()->sync($request->accounts);
        if ($post->getChanges() || ($mediaSync['attached'] || $mediaSync['detached']) || ($accountsSync['attached'] || $accountsSync['detached'])) {
            $log = $this->updateLog($request);
            $post->update(['logs' => $log]);
        }
    }
    public function storeLog($request)
    {
        $now = Carbon::now()->toDateTimeString();
        if ($request->draft) {
            $message = "Post saved as draft at {$now}";
        } else {
            $message = "Post saved and scheduled at {$now} to be published at {$request->schedule_date}";
        }
        return [['status' => 'info', 'message' => $message]];
    }

    public function updateLog($request)
    {
        $now = Carbon::now()->toDateTimeString();
        if ($request->draft) {
            $message = "Post updated and saved as draft at {$now}";
        } else {
            $message = "Post updated and scheduled at {$now} to be published at {$request->schedule_date}";
        }
        return [['status' => 'info', 'message' => $message]];
    }
}
