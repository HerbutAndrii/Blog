<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function before(User $user) 
    {
        if($user->isAdministrator()) {
            return true;
        }

        return null;
    }

    public function update(User $user, Post $post) 
    {
        return $user->id === $post->user->id;
    }

    public function delete(User $user, Post $post) 
    {
        return $user->id === $post->user->id;
    }
}
