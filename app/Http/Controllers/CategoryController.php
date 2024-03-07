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

    public function edit(Category $category) {
        $element = [
            'self' => $category,
            'name' => 'category',
            'updateUrl' => route('admin.category.update', $category)
        ];

        return view('admin.edit', compact('element'));
    }

    public function update(CategoryRequest $request, Category $category) {
        $category->update(['name' => $request->category_name]);

        return redirect(route('admin.category.show'));
    }

    public function destroy(Category $category) {
        $category->delete();

        return response()->json(['success' => true]);
    }
}
