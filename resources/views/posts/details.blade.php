@extends('layout')
@section('title', 'Details')
@include('header')
@section('content')
    <h1 style="margin-bottom: 5px">{{ $post->title }}</h1> 
    <h2 style="text-align: center;">
        <a href="{{ route('category.show', $post->category) }}" style="text-decoration: none; color: #7878bd">
            {{ $post->category->name }}
        </a>
    </h2>
    <h3 style="text-align: center; color: #555">
        {{ auth()->user()->name == $post->user->name ? 'You' : $post->user->name }} | {{ $date->year }} | {{ $date->format('F') }} {{ $date->day }} | {{ $date->format('H:i') }} | {{ $post->likes->count() }} likes
    </h3> 
    <fieldset>
        <img class="preview-details" src="{{ asset('storage/previews/' . $post->preview) }}" alt="Preview">
        <p style="text-align: center; font-size: 30px">{{ $post->content }}</p>
    </fieldset>
    <div class="like">
        @if(!$like)
            <form action="{{ route('post.like', $post) }}" method="POST">
                @csrf
                <button class="button-like" type="submit">Like</button>
            </form>
        @else
            <form action="{{ route('post.unlike', $post) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="button-like" type="submit">Unlike</button>
            </form> 
        @endif
    </div>
    <div class="tags">
        <h2>Tags</h2>
        @if(! $post->tags()->exists())
            <div style="font-size: 30px">No tags</div>
        @else
            @foreach($post->tags as $tag)
                <a href="{{ route('tag.show', $tag) }}">{{ $tag->name}}</a>
            @endforeach
        @endif
    </div>
    @if($post->user->id == auth()->user()->id)
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
        <button class="button-comment" type="submit" value="commnet" name="submit">Add</button>
    </form>
    @if(! $post->comments()->exists())
        <h2 style="text-align: center;">There are no comments here yet</h2>
    @else
        @foreach($post->comments as $comment)
            @if($comment->user->id == auth()->user()->id)
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
                        <button type="submit" class="button-comment" value="edit_comment" name="submit">Update</button>
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
                        <a href="{{ route('category.show', $post->category) }}" style="text-decoration: none">
                            <h3>{{ $post->category->name }}</h3>
                        </a>
                        <h4>{{ $post->likes()->count() }} likes</h4>
                        <p>{{ strlen($post->content) > 200 ? substr($post->content, 0, 200) . '...' : $post->content }}</p>
                        <a class="details-link" href="{{ route('post.show', $post) }}">Read more</a>
                    </div>
                @endforeach
            </div>
        @endif
@endsection