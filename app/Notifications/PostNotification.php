<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Cache;

class PostNotification extends Notification
{
    use Queueable;

    public $status;
    public $post_id;

    public function __construct($post, $status)
    {
        $this->status = $status;
        $this->message = "Post #$post->id has $status update ";
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        Cache::forget('notifications.' . $notifiable->id);
        return ['type' => 'post', 'message' => $this->message];
    }

    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage([
            'status' => $this->status,
            'message' => $this->message,
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
