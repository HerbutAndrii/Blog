@extends('layout')
@section('title', 'Welcome')
@section('content')
    <h1>Blog</h1>
    <a href="{{ route('auth.loginView') }}">Log in</a>
    <a href="{{ route('auth.registerView') }}">Sign up</a>
@endsection