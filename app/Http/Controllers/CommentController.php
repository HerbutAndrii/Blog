<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Post $post) {
        $comment = new Comment($request->only('content'));
        $comment->post()->associate($post);
        $comment->user()->associate(auth()->user());
        $comment->save();
        
        return response()->json([
            'comment' => $comment,
            'commentCount' => $comment->post->comments()->count()
        ]);
    }

    public function update(CommentRequest $request, Comment $comment) {
        $comment->content = $request->edit_content;
        $comment->save();

        return response()->json(['comment' => $comment]);
    }

    public function destroy(Comment $comment) {
        $comment->delete();

        return response()->json([
            'commentCount' => $comment->post->comments()->count()
        ]);
    }
}
