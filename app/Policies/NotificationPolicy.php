<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    public function read(User $user, Notification $notification)
    {
        return $user->id == $notification->notifiable_id;
    }

    public function delete(User $user, Notification $notification)
    {
        return $user->id == $notification->notifiable_id;
    }

}
