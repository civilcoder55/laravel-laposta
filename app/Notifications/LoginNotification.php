<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Cache;

class LoginNotification extends Notification
{
    use Queueable;

    public $from;
    public $on;

    public function __construct($from, $on)
    {
        $this->from = $from;
        $this->on = $on;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        Cache::forget('notifications.' . $notifiable->id);
        return ['type' => 'login', 'message' => "New Login From $this->from On $this->on"];
    }

    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage([
            'status' => 'warning',
            'message' => "New Login From $this->from On $this->on",
        ]))->onConnection('sync');
    }

    public function broadcastType()
    {
        return 'login';
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

}
