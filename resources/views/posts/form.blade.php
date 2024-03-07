@extends('layout')
@section('title', 'Form')
@section('content')
    @include('header')
    <h1>{{ isset($post) ? 'Edit post' : 'Create post' }}</h1> <hr>
    <form action="{{ isset($post) ? route(url()->current() === route('admin.post.edit', $post) ? 'admin.post.update' : 'post.update', $post) : route('post.store') }}" method="POST" enctype="multipart/form-data">
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
            <div id="category-message" style="color: #029af8; font-size: 20px; margin-top: 20px"></div>
            <select name="category" id="select-category">
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
            <input type="text" name="category_name" placeholder="Add a new category" id="category-input">
            <button class="add-category" type="button" id="add-category">Add</button>
            <div style="color: red; font-size: 20px" id="category-error"></div>
        </div>
        <label>
            Tags <br> 
            <div id="tag-message" style="color: #029af8; font-size: 20px; margin-top: 20px"></div>
            <select name="tags[]" id="select-tag" multiple>
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
            <input type="text" name="tag_name" placeholder="Add a new tag" id="tag-input">
            <button class="add-tag" type="button" id="add-tag">Add</button>
            <div style="color: red; font-size: 20px" id="tag-error"></div>        
        </div>
        <button type="submit" class="button-post">{{ isset($post) ? 'Update' : 'Create' }}</button>
    </form>

    <script type="text/javascript">
        function categoryStoreRequest () {
            $.ajax({
                type: 'POST',
                url: "{{ route('category.store') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    category_name: $('#category-input').val()
                },
                success: function (data) {
                    $('#category-input').val('');
                    $('#select-category').append('<option value="' + data.category.name + '">' + data.category.name + '</option>');
                    $('#category-message').text('Category "' + data.category.name + '" was created successfully');
                },
                error: function(err) {
                    let error = err.responseJSON;
                    $.each(error.errors, function (index, value) {
                        $('#category-error').text(value);
                    });
                    console.log(error.responseText);
                }
            });
        }

        function tagStoreRequest() {
            $.ajax({
                type: 'POST',
                url: "{{ route('tag.store') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    tag_name: $('#tag-input').val()
                },
                success: function (data) {
                    $('#tag-input').val('');
                    $('#select-tag').append('<option value="' + data.tag.id + '">' + data.tag.name + '</option>');
                    $('#tag-message').text('Tag "' + data.tag.name + '" was created successfully');
                },
                error: function(err) {
                    let error = err.responseJSON;
                    $.each(error.errors, function (index, value) {
                        $('#tag-error').text(value);
                    })
                    console.log(error.responseText);
                }
            });
        }

        $(document).ready(function () {
            $('#category-input').keypress(function (event) {
                if(event.which == 13) {
                    event.preventDefault();
                    categoryStoreRequest();
                }
            });

            $('#tag-input').keypress(function (event) {
                if(event.which == 13) {
                    event.preventDefault();
                    tagStoreRequest();
                }
            });

            $('#add-category').click(function (event) {
                event.preventDefault();
                categoryStoreRequest();
            });

            $('#add-tag').click(function (event) {
                event.preventDefault();
                tagStoreRequest();
            });
        });
    </script>
@endsection