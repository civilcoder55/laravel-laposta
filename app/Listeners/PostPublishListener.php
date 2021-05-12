<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\PostNotification;

class PostPublishListener
{

    public function handle($event)
    {
        $this->createLog($event);
        User::where(['id' => $event->post->user_id])->first()->notify(new PostNotification($event->post));
    }

    private function createLog($event)
    {
        if ($event->error) {
            $log = [[
                'status' => 'danger',
                'message' => "Post can't be published with {$event->account->name} {$event->account->platform} {$event->account->type} reason: $event->error",
            ]];
            $status = (($event->post->status == 'failed' || $event->post->status == 'pending') ? 'failed' : 'critical');
            $event->post->update(['status' => $status, 'locked' => 1, 'logs' => $log]);
        } else {
            $log = [[
                'status' => 'success',
                'message' => "Post published successfully at {$event->post->schedule_date} to {$event->account->name} {$event->account->platform} {$event->account->type}",
            ]];
            $status = (($event->post->status == 'succeeded' || $event->post->status == 'pending') ? 'succeeded' : 'critical');
            $event->post->update(['status' => $status, 'locked' => 1, 'logs' => $log]);
        }

    }
}
