<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentLike;

class CommentLikeController extends Controller
{
    public function like(Comment $comment) {
        $user = auth()->user();

        if(! $comment->isLikedByUser()) {
            $like = new CommentLike(['user_id' => $user->id]);
            $comment->likes()->save($like);
        }

        return response()->json([
            'likes' => $comment->likes()->count(),
        ]);
    }

    public function unlike(Comment $comment) {
        $user = auth()->user();

        if($like = $comment->likes()->where('user_id', $user->id)) {
            $like->delete();
        }

        return response()->json([
            'likes' => $comment->likes()->count(),
        ]);
    }
}
