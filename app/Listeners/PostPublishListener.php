<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\PostNotification;

class PostPublishListener
{

    public function handle($event)
    {
        User::where(['id' => $event->post->user_id])->first()->notify(new PostNotification($event->post, $event->status));
    }
}
