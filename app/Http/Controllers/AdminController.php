<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;

class AdminController extends Controller
{
    public function index() {
        $counts = [
            'users' => User::count(),
            'posts' => Post::count(),
            'comments' => Comment::count(),
            'categories' => Category::count(),
            'tags' => Tag::count()     
        ];

        return view('admin.index', compact('counts'));
    }

    public function users() {
        $users = User::paginate(10);

        return view('admin.users', compact('users'));
    }

    public function posts() {
        $posts = Post::paginate(10);

        return view('admin.posts', compact('posts'));
    }

    public function comments() {
        $comments = Comment::paginate(10);

        return view('admin.comments', compact('comments'));
    }

    public function categories() {
        $categories = Category::paginate(10);

        return view('admin.categories', compact('categories'));
    }

    public function tags() {
        $tags = Tag::paginate(10);

        return view('admin.tags', compact('tags'));
    }
}
