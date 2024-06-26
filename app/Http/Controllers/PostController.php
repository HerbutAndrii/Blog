<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Jobs\PostNotificationJob;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index() {
        $posts = Post::with('category', 'likes')
            ->orderByDesc('updated_at')
            ->paginate(6);

        return view('posts.index', compact('posts'))
            ->with('title', 'All posts');
    }

    public function userIndex() {
        $posts = Post::with('category', 'likes')
            ->orderByDesc('updated_at')
            ->where('user_id', auth()->user()->id)
            ->paginate(6);

        return view('posts.index', compact('posts'))
            ->with('title', 'My posts');
    }

    public function show(Post $post) {
        $date = Carbon::parse($post->updated_at);

        $relatedPosts = $post->category->posts()
                        ->with('category', 'likes')
                        ->orderByDesc('updated_at')
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
        $post = new Post($request->only('title', 'content'));
        $post->user()->associate(auth()->user());
        $post->category()->associate(Category::firstOrCreate(['name' => $request->category]));

        $this->handlePreview($request, $post);

        $post->save();

        if($request->has('tags')) {
            $post->tags()->attach($request->tags);
        }

        dispatch(new PostNotificationJob($request->user(), $post));

        return redirect(route('post.user.index'));
    }

    public function edit(Post $post) {
        $this->authorize('update', $post);

        $categories = Category::all();
        $tags = Tag::all();

        if(url()->current() === route('admin.post.edit', $post)) {
            session(['url' => url()->previous()]);
        }

        return view('posts.form', compact('post', 'categories', 'tags'));
    }

    public function update(PostRequest $request, Post $post) {
        $this->authorize('update', $post);

        $post->fill($request->only('title', 'content'));
        $post->category()->associate(Category::firstOrCreate(['name' => $request->category]));
        
        $this->handlePreview($request, $post);

        $post->save();

        if($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        if(url()->current() === route('admin.post.update', $post)) {
            $url = session('url');
            session()->forget('url');

            return redirect($url);
        }
        
        return redirect(route('post.user.index'));
    } 

    public function destroy(Request $request, Post $post) {
        $this->authorize('delete', $post);

        if($post->preview != 'default-preview.jpg') {
            Storage::delete('public/previews/' . $post->preview);
        }

        $post->delete();

        if($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect(route('post.user.index'));
    }

    private function handlePreview(PostRequest $request, Post $post) {
        if($request->hasFile('preview')) {
            $fileName = $request->file('preview')->hashName();
            $request->file('preview')->storeAs('public/previews', $fileName);
            $post->preview = $fileName;
        } else {
            Storage::copy('/public/layouts/default-preview.jpg', 'public/previews/default-preview.jpg');
        }
    }
}
