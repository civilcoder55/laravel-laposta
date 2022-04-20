<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Cache;

class LoginNotification extends Notification
{
    use Queueable;

    public $message;
    public $link;
    public function __construct($browser, $os)
    {
        $this->message = "New Login From $browser On $os";
        $this->link = route('profile.index') . "#sessionsTable";
    }

    public function via($notifiable)
    {
        return ['broadcast', 'database'];
    }

    public function toDatabase($notifiable): array
    {
        Cache::forget('notifications.' . $notifiable->id);
        return ['status' => 'warning', 'type' => 'login', 'message' => $this->message, 'link' => $this->link];
    }

    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage([
            'id' => $this->id,
            'data' => [
                'status' => 'warning',
                'type' => 'login',
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
