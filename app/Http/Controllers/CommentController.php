<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        session(['editComment' => $comment->id]);
        return back();
    }

    public function update(Request $request, Comment $comment) {
        $validator = Validator::make($request->all(), [
            'edit_content' => ['required', 'string'],
        ]);

        if($validator->fails()) {
            session(['editComment' => $comment->id]);
            return back()->withErrors($validator)->withInput();
        }

        $comment->content = $request->edit_content;
        $comment->save();
        
        $request->session()->forget('editComment');
        return redirect(route('post.show', $comment->post));
    }

    public function destroy(Comment $comment) {
        $comment->delete();
        return back();
    }
}
