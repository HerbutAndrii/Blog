@extends('layout')
@section('title', 'Admin')
@section('content')
    @include('header')
    <h1>Admin panel</h1> <hr>
    <div class="list-block">
        <h2>Users ({{ $counts['users'] }})</h2> 
        <a href="{{ route('admin.user.show') }}" class="show">
            <i class="fa-solid fa-eye"></i>
            <span style="margin-left: 5px">Show</span>
        </a> 
    </div> <hr>
    <div class="list-block">
        <h2>Posts ({{ $counts['posts'] }})</h2> 
        <a href="{{ route('admin.post.show') }}" class="show">
            <i class="fa-solid fa-eye"></i>
            <span style="margin-left: 5px">Show</span>
        </a>
    </div> <hr>
    <div class="list-block">
        <h2>Comments ({{ $counts['comments'] }})</h2> 
        <a href="{{ route('admin.comment.show') }}" class="show">
            <i class="fa-solid fa-eye"></i>
            <span style="margin-left: 5px">Show</span>
        </a> 
    </div> <hr>
    <div class="list-block">
        <h2>Categories ({{ $counts['categories'] }})</h2> 
        <a href="{{ route('admin.category.show') }}" class="show">
            <i class="fa-solid fa-eye"></i>
            <span style="margin-left: 5px">Show</span>
        </a> 
    </div> <hr>
    <div class="list-block">
        <h2>Tags ({{ $counts['tags'] }})</h2> 
        <a href="{{ route('admin.tag.show') }}" class="show">
            <i class="fa-solid fa-eye"></i>
            <span style="margin-left: 5px">Show</span>
        </a> 
    </div> <hr>
@endsection