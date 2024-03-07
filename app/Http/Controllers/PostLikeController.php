<?php

namespace App\Http\Controllers;

use App\Models\PostLike;
use App\Models\Post;

class PostLikeController extends Controller
{
    public function like(Post $post) {
        $user = auth()->user();

        if(! $post->likes()->where('user_id', $user->id)->exists()) {
            $like = new PostLike(['user_id' => $user->id]);
            $post->likes()->save($like);
        }

        return response()->json(['likes' => $post->likes()->count()]);
    }

    public function unlike(Post $post) {
        $user = auth()->user();

        if($like = $post->likes()->where('user_id', $user->id)) {
            $like->delete();
        }

        return response()->json(['likes' => $post->likes()->count()]);
    }
}
