<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function before(User $user) 
    {
        if($user->isAdministrator()) {
            return true;
        }

        return null;
    }
    
    public function update(User $user, Comment $comment) {
        return $user->id === $comment->user->id;
    }

    public function delete(User $user, Comment $comment) {
        return $user->id === $comment->user->id;
    }
}
