<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Models\Tag;

class TagController extends Controller
{
    public function show(Tag $tag) {
        $posts = $tag->posts()->orderByDesc('updated_at')->paginate(6);

        if($posts->isEmpty()) {
            return view('posts.index')->with('title', 'Nothing found');
        }

        return view('posts.index', compact('posts'))
            ->with('title', "Posts with tag $tag->name");
    }

    public function store(TagRequest $request) {
        $tag = new Tag(['name' => $request->tag_name]);
        $tag->save();

        return response()->json(['tag' => $tag]);
    }

    public function edit(Tag $tag) {
        $element = [
            'self' => $tag,
            'name' => 'tag',
            'updateUrl' => route('admin.tag.update', $tag)
        ];

        return view('admin.edit', compact('element'));
    }

    public function update(TagRequest $request, Tag $tag) {
        $tag->update(['name' => $request->tag_name]);

        return redirect(route('admin.tag.show'));
    }

    public function destroy(Tag $tag) {
        $tag->delete();

        return response()->json(['success' => true]);
    }
}
