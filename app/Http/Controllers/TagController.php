<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Tag;

class TagController extends Controller
{
    public function store(PostRequest $request) {
        $tag = new Tag();
        $tag->name = $request->tag_name;
        $tag->save();
        return back()->withInput();
    }
}
