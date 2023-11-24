<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index() {
        $posts = Post::orderByDesc('updated_at')->paginate(6);
        return view('posts.index', compact('posts'));
    }

    public function userIndex() {
        $posts = Post::orderByDesc('updated_at')
                ->where('user_id', auth()->user()->id)
                ->paginate(6);
        return view('posts.index', compact('posts'));
    }

    public function show(Post $post) {
        $date = Carbon::parse($post->updated_at);
        $relatedPosts = Post::with('tags')
                        ->orderByDesc('updated_at')
                        ->where('category_id', $post->category_id)
                        ->where('id', '!=', $post->id)
                        ->take(3)
                        ->get();
        return view('posts.details', compact('post', 'date', 'relatedPosts'));
    }

    public function create() {
        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.form', compact('categories', 'tags'));
    }

    public function store(PostRequest $request) {
        $post = new Post();
        $post->fill($request->only('title', 'content'));
        $post->user()->associate(auth()->user());
        $category = Category::where('name', $request->category)->first();
        $post->category()->associate($category);

        if($request->hasFile('preview')) {
            $fileName = $request->file('preview')->getClientOriginalName();
            $request->file('preview')->storeAs('public/previews', $fileName);
            $post->preview = $fileName;
        } else {
            Storage::put('public/previews/default-preview.avif', 
                    Storage::get('/public/layouts/default-preview.avif'));
        }

        $post->save();

        if($request->has('tags')) {
            $post->tags()->attach($request->tags);
        }

        return redirect(route('post.user.index'));
    }

    public function edit(Post $post) {
        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.form', compact('post', 'categories', 'tags'));
    }

    public function update(PostRequest $request, Post $post) {
        $post->title = $request->title;
        $post->content = $request->content;
        $category = Category::where('name', $request->category)->first();
        $post->category()->associate($category);
        
        if($request->hasFile('preview')) {
            $fileName = $request->file('preview')->getClientOriginalName();
            $request->file('preview')->storeAs('public/previews', $fileName);
            $post->preview = $fileName;
        } else {
            Storage::put('public/previews/default-preview.avif', 
                    Storage::get('/public/layouts/default-preview.avif'));
        }

        $post->save();

        if($request->has('tags')) {
            $post->tags()->attach($request->tags);
        }
        
        return redirect(route('post.user.index'));
    } 

    public function destroy(Post $post) {
        $post->comments()->delete();
        $post->tags()->detach();
        $post->delete();
        return redirect(route('post.user.index'));
    }
}
