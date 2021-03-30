<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class PostNotify extends Notification
{
    use Queueable;

    public $post;
    public $status;
    public $post_id;

    public function __construct($post, $status)
    {
        $this->post = $post;
        $this->status = $status ? 'succeed' : 'failed';;
        $this->post_id = $this->post->id;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }


    public function toDatabase($notifiable): array
    {
        Cache::forget('notifications.' . $notifiable->id);
        return ['type' => 'post', 'message' => "Post with id $this->post_id publish $this->status"];
    }

    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage([
            'message' => "Post with id $this->post_id publish $this->status",
        ]))->onConnection('sync');
    }

    public function broadcastType()
    {
        return 'post';
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
