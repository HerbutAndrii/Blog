@extends('layout')
@section('title', 'Categories')
@section('content')
    @include('header')
    <h1>Categories</h1> <hr>
    @foreach($categories as $category)
        <div class="list-block">
            <h2>{{ $category->name }}</h2> 
            <a href="{{ route('category.show', $category) }}" class="show">
                <i class="fa-solid fa-eye"></i>
                <span style="margin-left: 5px">Show posts</span>
            </a> 
        </div> <hr>
    @endforeach
    {{ $categories->links() }}
@endsection