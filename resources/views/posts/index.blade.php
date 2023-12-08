@extends('layout')
@section('title', 'Posts')
@include('header')
@section('content')
    <h1>{{ $title }}</h1> <hr>
    @isset($posts)
        <div class="blog-cards-container">
            @foreach($posts as $post)
                <div class="blog-card">
                    <img src="{{ asset('storage/previews/' . $post->preview) }}" alt="Preview">
                    <h2>{{ $post->title }}</h2>
                    <h3>
                        <a href="{{ route('category.show', $post->category) }}" style="text-decoration: none; color: #7878bd">
                            {{ $post->category->name }}
                        </a>
                    </h3>
                    <h4>{{ $post->likes()->count() }} likes</h4>
                    <p>{{ strlen($post->content) > 200 ? substr($post->content, 0, 200) . '...' : $post->content }}</p>
                    <a class="details-link" href="{{ route('post.show', $post) }}">
                        <span style="margin-right: 10px">Read more</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            @endforeach
        </div>
        {{ $posts->links() }}
    @endisset
@endsection