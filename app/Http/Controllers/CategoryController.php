<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category) {
        $posts = $category->posts()->orderByDesc('updated_at')->paginate(6);

        if($posts->isEmpty()) {
            return view('posts.index')->with('title', 'Nothing found');
        }

        return view('posts.index', compact('posts'))
            ->with('title', "Posts with category $category->name");
    }

    public function store(CategoryRequest $request) {
        $category = new Category(['name' => $request->category_name]);
        $category->save();

        return response()->json(['category' => $category]);
    }
}
