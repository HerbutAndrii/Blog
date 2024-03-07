@extends('layout')
@section('title', 'Admin')
@section('content')
    @include('header')
    <h1>Edit {{ $element['name'] }}</h1> <hr>
    <form action="{{ $element['updateUrl'] }}" method="POST">
        @csrf
        @method('PUT') 
        <label>
            @if($element['name'] === 'comment')
                Content <br>
                <textarea name="content" rows="8" cols="33" placeholder="Content...">{{ old('edit_content', $element['self']['content']) }}</textarea> <br>
            @else
                Name <br>
                <input type="text" name="{{ $element['name'] . '_name' }}" value="{{ old($element['name'] . '_name', $element['self']['name']) }}" placeholder="Name"> <br>
            @endif
        </label>
        @if($errors->any())
            @foreach($errors->all() as $error)
                <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $error }}</div>
            @endforeach
        @endif
        <button type="submit" class="button-post">Update</button>
    </form>
@endsection