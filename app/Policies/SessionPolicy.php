<?php

namespace App\Policies;

use App\Models\Session;
use App\Models\User;

class SessionPolicy
{

    public function delete(User $user, Session $session)
    {
        return $user->id == $session->user_id;
    }

}
