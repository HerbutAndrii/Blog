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

    public function edit(Comment $comment) {
        $element = [
            'self' => $comment,
            'name' => 'comment',
            'updateUrl' => route('admin.comment.update', $comment)
        ];

        return view('admin.edit', compact('element'));
    }

    public function update(CommentRequest $request, Comment $comment) {
        $this->authorize('update', $comment);

        $comment->update(['content' => $request->content]);

        if($request->ajax()) {
            return response()->json(['comment' => $comment]);   
        }

        return redirect(route('admin.comment.show'));
    }

    public function destroy(Comment $comment) {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json([
            'commentCount' => $comment->post->comments()->count()
        ]);
    }
}
