@extends('layout')
@section('title', 'Form')
@include('header')
@section('content')
    <h1>{{ isset($post) ? 'Edit post' : 'Create post' }}</h1> <hr>
    <form action="{{ isset($post) ? route('post.update', $post) : route('post.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @isset($post) @method('PUT') @endisset
        <label>
            Title <br>
            <input type="text" name="title" value="{{ old('title', isset($post) ? $post->title : '') }}" placeholder="Title"> <br>
        </label>
        @error('title')
            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <label>
            Content <br>
            <textarea name="content" rows="5" cols="33">{{ old('content', isset($post) ? $post->content : '') }}</textarea> <br>
        </label>
        @error('content')
            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <label>
            Preview <br>
            @isset($post) 
                <img class="preview" src="{{ asset('storage/previews/' . $post->preview) }}" alt="Preview"> <br>
            @endisset
            <input type="file" name="preview" accept="image/*"> <br> <br>
        </label>
        <fieldset>
            <legend>
                <strong>Select category</strong> 
            </legend>
            @foreach($categories as $category)
                <label>
                    <input type="radio" name="category" value="{{ $category->name }}" 
                    {{ isset($post) && $post->category->name == $category->name  ||  old('category') == $category->name ? 'checked' : '' }}>
                    {{ $category->name }} <br>
                </label>
            @endforeach
        </fieldset>
        @error('category')
            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <div class="add-post-category">
            <button class="button-post" type="submit">{{ isset($post) ? 'Update' : 'Create' }}</button>
            </form>
            <form action="{{ route('category.store') }}" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Add a new category">
                <button class="add-category" type="submit">Add</button>
            </form>
        </div>
        @error('name')
            <div style="color: red; font-size: 20px; margin-left: 250px" >{{ $message }}</div>
        @enderror
@endsection