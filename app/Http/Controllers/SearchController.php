<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

class SearchController extends Controller
{
    public function __invoke(SearchRequest $request) {
        if($request->ajax()) {
            $posts = Post::where('title', 'like', '%'.$request->search.'%')
                ->orderByDesc('updated_at')
                ->get();
                
            return response()->json($posts);
        }

        $posts = Post::where('title', 'like', '%'.$request->search.'%')
            ->orderByDesc('updated_at')
            ->get();

        if($posts->isNotEmpty()) {
            return view('posts.index', compact('posts'))
                ->with('title', 'Searching results');
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
