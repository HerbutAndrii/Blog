<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\PostRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function store(PostRequest $request) {
        $category = new Category();
        $category->name = $request->category_name;
        $category->save();
        return back()->withInput();
    }
}
