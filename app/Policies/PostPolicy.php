<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Post $post)
    {
        return $user->id == $post->user_id;
    }

    public function edit(User $user, Post $post)
    {
        return $user->id == $post->user_id && $post->locked == 0;
    }

    public function delete(User $user, Post $post)
    {
        return $user->id == $post->user_id;
    }

}
