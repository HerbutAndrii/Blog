<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

class SearchController extends Controller
{
    public function __invoke(SearchRequest $request) {
        if($post = Post::where('title', $request->search)->first()) {
            return redirect(route('post.show', $post));
        }

        if($category = Category::where('name', $request->search)->first()) {
            return redirect(route('category.show', $category));
        }   

        $request['search'] = strtolower(str_replace(' ', '_', $request->search));

        if($request->search[0] != '#') {
            $request['search'] = '#' . $request->search;
        }
        
        if($tag = Tag::where('name', $request->search)->first()) {
            return redirect(route('tag.show', $tag));
        }

        return view('posts.index')->with('title', 'Nothing found');
    }
}
