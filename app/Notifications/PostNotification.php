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
    public $message;
    public $link;

    public function __construct($post)
    {
        $this->status = $post->status == 'succeeded' ? 'success' : 'error';
        $this->message = "Post #$post->id status changed to $post->status ";
        $this->link = route('posts.show', $post->id);
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        Cache::forget('notifications.' . $notifiable->id);
        return ['status' => $this->status, 'type' => 'post', 'message' => $this->message, 'link' => $this->link];
    }

    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage([
            'id' => $this->id,
            'data' => [
                'status' => $this->status, // for front-end
                'type' => 'post',
                'message' => $this->message,
                'link' => $this->link,
            ],
            'created_at' => 'just now',
        ]));
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
