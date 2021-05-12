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
        $this->status = $post->status == 'succeeded' ? 'success' : 'danger';
        $this->message = "Post #$post->id status changed to $post->status ";
        $this->link = route('posts.review', $post->id);
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        Cache::forget('notifications.' . $notifiable->id);
        return ['type' => 'post', 'message' => $this->message, 'link' => $this->link];
    }

    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage([
            'status' => $this->status, // for front-end
            'link' => $this->link,
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
