<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Post $post) {
        $comment = new Comment();
        $comment->content = $request->content;
        $comment->post()->associate($post);
        $comment->user()->associate(auth()->user());
        $comment->save();
        return back();
    }

    public function edit(Comment $comment) {
        return back()->with('editComment', $comment->id);
    }

    public function update(CommentRequest $request, Comment $comment) {
        $comment->content = $request->content;
        $comment->save();
        $request->session()->forget('editComment');
        return redirect(route('post.show', $comment->post));
    }

    public function destroy(Comment $comment) {
        $comment->delete();
        return back();
    }
}
