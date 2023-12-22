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
            <textarea name="content" rows="8" cols="33" placeholder="Content...">{{ old('content', isset($post) ? $post->content : '') }}</textarea> <br>
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
        </label> <br>
        <label>
            Category <br> 
            <select name="category">
                <option value="">Select category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->name }}" {{ isset($post) && $post->category->name == $category->name  ||  old('category') == $category->name ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </label> <br>
        @error('category')
            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <div class="new-category-tag">
            <input type="text" name="category_name" placeholder="Add a new category">
            <button formaction="{{ route('category.store') }}" class="add-category" type="submit">Add</button>
            @error('category_name')
                <div style="color: red; font-size: 20px" >{{ $message }}</div>
            @enderror
        </div>
        <label>
            Tags <br> 
            <select name="tags[]" multiple>
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" 
                    {{ isset($post) && in_array($tag->name, $post->tags->pluck('name')->toArray())  || in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
                        {{ $tag->name }}
                    </option>
                @endforeach
            </select>
        </label>
        @error('tags')
            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <div class="new-category-tag">
            <input type="text" name="tag_name" placeholder="Add a new tag">
            <button formaction="{{ route('tag.store') }}" class="add-tag" type="submit">Add</button>
            @error('tag_name')
                <div style="color: red; font-size: 20px" >{{ $message }}</div>
            @enderror        
        </div>
        <button class="button-post" type="submit" value="post">{{ isset($post) ? 'Update' : 'Create' }}</button>
    </form>
@endsection