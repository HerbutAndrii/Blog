<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

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

    public function edit(Category $category) {
        $element = [
            'self' => $category,
            'name' => 'category',
            'updateUrl' => route('admin.category.update', $category)
        ];

        return view('admin.edit', compact('element'));
    }

    public function update(CategoryRequest $request, Category $category) {
        if(! $request->user()->isAdministrator()) {
            abort(403);
        }

        $category->update(['name' => $request->category_name]);

        return redirect(route('admin.category.show'));
    }

    public function store(CategoryRequest $request) {
        $category = new Category(['name' => $request->category_name]);
        $category->save();

        return response()->json(['category' => $category]);
    }

    public function destroy(Request $request, Category $category) {
        if(! $request->user()->isAdministrator()) {
            abort(403);
        }
        
        $category->delete();

        return response()->json(['success' => true]);
    }
}
