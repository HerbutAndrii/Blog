@extends('layout')
@section('title', 'Admin')
@section('content')
    @include('header')
    <h1>Admin panel</h1> <hr>
    <div class="admin-panel">
        <h2 style="font-size: 30px">Users ({{ $counts['users'] }})</h2> 
        <a href="{{ route('admin.user.show') }}" class="admin-show">
            <i class="fa-solid fa-eye"></i>
            <span style="margin-left: 5px">Show</span>
        </a> 
    </div> <hr>
    <div class="admin-panel">
        <h2 style="font-size: 30px">Posts ({{ $counts['posts'] }})</h2> 
        <a href="{{ route('admin.post.show') }}" class="admin-show">
            <i class="fa-solid fa-eye"></i>
            <span style="margin-left: 5px">Show</span>
        </a>
    </div> <hr>
    <div class="admin-panel">
        <h2 style="font-size: 30px">Comments ({{ $counts['comments'] }})</h2> 
        <a href="{{ route('admin.comment.show') }}" class="admin-show">
            <i class="fa-solid fa-eye"></i>
            <span style="margin-left: 5px">Show</span>
        </a> 
    </div> <hr>
    <div class="admin-panel">
        <h2 style="font-size: 30px">Categories ({{ $counts['categories'] }})</h2> 
        <a href="{{ route('admin.category.show') }}" class="admin-show">
            <i class="fa-solid fa-eye"></i>
            <span style="margin-left: 5px">Show</span>
        </a> 
    </div> <hr>
    <div class="admin-panel">
        <h2 style="font-size: 30px">Tags ({{ $counts['tags'] }})</h2> 
        <a href="{{ route('admin.tag.show') }}" class="admin-show">
            <i class="fa-solid fa-eye"></i>
            <span style="margin-left: 5px">Show</span>
        </a> 
    </div> <hr>
@endsection