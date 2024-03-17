@extends('layout')
@section('title', 'Categories')
@section('content')
    @include('header')
    <h1>Tags</h1> <hr>
    @foreach($tags as $tag)
        <div class="list-block">
            <h2>{{ $tag->name }}</h2> 
            <a href="{{ route('tag.show', $tag) }}" class="show">
                <i class="fa-solid fa-eye"></i>
                <span style="margin-left: 5px">Show posts</span>
            </a> 
        </div> <hr>
    @endforeach
    {{ $tags->links() }}
@endsection