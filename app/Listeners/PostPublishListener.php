<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\PostNotify;

class PostPublishListener
{

    public function handle($event)
    {
        $event->post->update(['success' => $event->status]);
        User::where(['id' => $event->post->user_id])->first()->notify(new PostNotify($event->post, $event->status));
    }
}
