<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function show(User $user) {
        $posts = Post::with('category', 'likes')
            ->where('user_id', $user->id)
            ->orderByDesc('updated_at')
            ->paginate(6);

        return view('posts.index', compact('posts', 'user'))
            ->with('title', "Posts of $user->name");
    }

    public function destroy(User $user) {
        if($user->avatar != 'default-avatar.jpg') {
            Storage::delete('public/avatars/' . $user->avatar);
        }

        $user->delete();
        
        return response()->json(['success' => true]);
    }
}
