@extends('layout')
@section('title', 'Posts')
@include('header')
@section('content')
    <h1>Posts</h1> <hr>
    <div class="blog-cards-container">
        @foreach($posts as $post)
            <div class="blog-card">
                <img src="{{ asset('storage/previews/' . $post->preview) }}" alt="Preview">
                <h2>{{ $post->title }}</h2>
                <h3>{{ $post->category->name }}</h3>
                <p>{{ strlen($post->content) > 200 ? substr($post->content, 0, 200) . '...' : $post->content }}</p>
                <a href="{{ route('post.show', $post) }}">Read more</a>
            </div>
        @endforeach
    </div>
    {{ $posts->links('vendor.pagination.bootstrap-4') }}
@endsection