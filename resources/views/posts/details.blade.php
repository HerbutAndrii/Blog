@extends('layout')
@section('title', 'Details')
@include('header')
@section('content')
    <h1>{{ $post->title }}</h1> 
    <h2>{{ $date->format('F') }} {{ $date->day }}, {{ $date->year }}, {{ $date->format('H:i') }}</h2>
    <hr>
    <h2>Preview</h2>  
    <img class="preview" src="{{ asset('storage/previews/' . $post->preview) }}" alt="Preview">
    <h2>Author: {{ $post->user->name == auth()->user()->name ? 'You' : $post->user->name  }}</h2>
    <h2>Title: {{ $post->title }}</h2>
    <h2>Content</h2> 
    <p>{{ $post->content }}</p>
    @if($post->user->name == auth()->user()->name)
        <a class="link-edit" href="{{ route('post.edit', $post) }}">Edit post</a>
        <form action="{{ route('post.destroy', $post) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="button-delete" type="submit">Delete post</button>
        </form>
    @endif
    <h2>Comments ({{ $post->comments()->count() }})</h2>
    <h3>Add a comment</h3>
    <form action="{{ route('comment.store', $post) }}" method="POST">
        @csrf
        <textarea name="content" rows="5" cols="33" placeholder="Comment..."></textarea> <br>
        @error('content')
            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <button class="button-comment" type="submit">Add</button>
    </form>
    @if(! $post->comments()->exists())
        <h3>There are no comments here yet</h3>
    @else
        @foreach($post->comments as $comment)
            @if($comment->user->name == auth()->user()->name)
                <div class="comment">
                    <img src="{{ asset('storage/avatars/' . $comment->user->avatar) }}" alt="avatar">
                    <h3>You</h3>
                    <div>{{ $comment->getDateAsCarbon()->diffForHumans() }}</div>
                </div>
                @if(session('editComment') == $comment->id)
                    <form action="{{ route('comment.update', $comment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <textarea name="content">{{ $comment->content }}</textarea> <br>
                        <button type="submit" class="button-comment">Update</button>
                    </form>
                @else
                    <p>{{ $comment->content }}</p>
                    <div style="display: flex; height: 55px">
                        <a href="{{ route('comment.edit', $comment) }}" class="link-edit">Edit comment</a>
                        <form action="{{ route('comment.destroy', $comment) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="button-delete" type="submit">Delete comment</button>
                        </form>
                    </div>
                @endif
            @else
            <div class="comment">
                <img src="{{ asset('storage/avatars/' . $comment->user->avatar) }}" alt="avatar">
                <h3>{{ $comment->user->name }}</h3>
                <div>{{ $comment->getDateAsCarbon()->diffForHumans() }}</div>
            </div>
            <p>{{ $comment->content }}</p>
            @endif
        @endforeach
    @endif
    <h1>Related posts</h1> <hr>
    <div class="blog-cards-container">
        @if($relatedPosts->isEmpty())
            <h3>There are no related posts here yet</h3>
        @else
            @foreach($relatedPosts as $post)
                <div class="blog-card">
                    <img src="{{ asset('storage/previews/' . $post->preview) }}" alt="Preview">
                    <h2>{{ $post->title }}</h2>
                    <p>{{ strlen($post->content) > 200 ? substr($post->content, 0, 200) . '...' : $post->content }}</p>
                    <a href="{{ route('post.show', $post) }}">Read more</a>
                </div>
            @endforeach
        @endif
    </div>
@endsection