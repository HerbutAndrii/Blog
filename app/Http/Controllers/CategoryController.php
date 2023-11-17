<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function store(CategoryRequest $request) {
        $category = new Category();
        $category->name = $request->name;
        $category->save();
        return back()->withInput();
    }
}
