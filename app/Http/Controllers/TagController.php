<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Tag;

class TagController extends Controller
{
    public function show(Tag $tag) {
        $posts = $tag->posts()->orderByDesc('updated_at')->paginate(6);

        return view('posts.index', compact('posts'))
            ->with('title', "Posts with tag $tag->name");
    }

    public function store(PostRequest $request) {
        $tag = new Tag(['name' => $request->tag_name]);
        $tag->save();

        return back()->withInput();
    }
}
