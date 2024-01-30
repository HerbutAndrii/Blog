<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;

class LikeController extends Controller
{
    public function like(Post $post) {
        $user = auth()->user();

        $like = new Like(['user_id' => $user->id]);
        $post->likes()->save($like);

        return response()->json(['likes' => $post->likes()->count()]);
    }

    public function unlike(Post $post) {
        $user = auth()->user();

        $post->likes()->where('user_id', $user->id)->delete();
        
        return response()->json(['likes' => $post->likes()->count()]);
    }
}
