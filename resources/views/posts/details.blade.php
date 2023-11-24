@extends('layout')
@section('title', 'Details')
@include('header')
@section('content')
    <h1 style="margin-bottom: 5px">{{ $post->title }}</h1> 
    <h2 style="text-align: center; color: #7878bd">{{ $post->category->name }}</h2>
    <h3 style="text-align: center; color: #555">{{ auth()->user()->name == $post->user->name ? 'You' : $post->user->name }}, {{ $date->format('F') }} {{ $date->day }}, {{ $date->year }}, {{ $date->format('H:i') }}</h3> <hr>
    <img class="preview-details" src="{{ asset('storage/previews/' . $post->preview) }}" alt="Preview">
    <fieldset>
        <p style="text-align: center; font-size: 30px">{{ $post->content }}</p>
    </fieldset>
    <div style="margin-bottom: 20px;">
        <h2>Tags</h2>
        <p>
            @foreach($post->tags as $tag)
                {{ $tag->name . ' '}} 
            @endforeach
        </p>
    </div>
    @if($post->user->name == auth()->user()->name)
        <div style="display: flex; height: 54px">
            <a class="link-edit" href="{{ route('post.edit', $post) }}">Edit post</a>
            <form action="{{ route('post.destroy', $post) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="button-delete" type="submit">Delete post</button>
            </form>    
        </div>
    @endif
    <h2>Comments ({{ $post->comments()->count() }})</h2>
    <form action="{{ route('comment.store', $post) }}" method="POST">
        @csrf
        <label>
            Add a comment <br>
            <textarea name="content" rows="5" cols="33" placeholder="Comment..."></textarea> <br>
        </label>
        @error('content')
            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <button class="button-comment" type="submit">Add</button>
    </form>
    @if(! $post->comments()->exists())
        <h2 style="text-align: center;">There are no comments here yet</h2>
    @else
        @foreach($post->comments as $comment)
            @if($comment->user->name == auth()->user()->name)
                <div class="comment">
                    <img src="{{ asset('storage/avatars/' . $comment->user->avatar) }}" alt="avatar">
                    <h3>You</h3>
                    <div style="color: #555;">
                        <strong>{{ $comment->getDateAsCarbon()->diffForHumans() }}</strong>
                    </div>
                </div>
                @if(session('editComment') == $comment->id)
                    <form action="{{ route('comment.update', $comment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <textarea name="edit_content">{{ old('edit_content', $comment->content) }}</textarea> <br>
                        @error('edit_content')
                            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
                        @enderror
                        <button type="submit" class="button-comment">Update</button>
                    </form>
                @else
                    <p>{{ $comment->content }}</p>
                    <div style="display: flex; height: 54px">
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
                <div style="color: #555;">
                    <strong>{{ $comment->getDateAsCarbon()->diffForHumans() }}</strong>
                </div>
            </div>
            <p>{{ $comment->content }}</p>
            @endif
        @endforeach
    @endif
    <h1>Related posts</h1> <hr>
        @if($relatedPosts->isEmpty())
            <h2 style="text-align: center;">There are no related posts here yet</h2>
        @else
            <div class="blog-cards-container">
                @foreach($relatedPosts as $post)
                    <div class="blog-card">
                        <img src="{{ asset('storage/previews/' . $post->preview) }}" alt="Preview">
                        <h2>{{ $post->title }}</h2>
                        <h3>{{ $post->category->name }}</h3>
                        <p>{{ strlen($post->content) > 200 ? substr($post->content, 0, 200) . '...' : $post->content }}</p>
                        <a href="{{ route('post.show', $post) }}">Read more</a>
                    </div>
                @endforeach
            </div>
        @endif
@endsection