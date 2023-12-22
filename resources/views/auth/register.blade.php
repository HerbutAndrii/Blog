@extends('layout')
@section('title', 'Register')
@section('content')
    <form class="auth-form" action="{{ route('auth.register') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <h2>Sign Up</h2>
        <label>
            Name
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your name"> <br>
        </label>
        @error('name')
            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <label>
            Password
            <input type="password" name="password" value="{{ old('password') }}" placeholder="Enter your password"> <br>
        </label>
        @error('password')
            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <label>
            Email
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email"> <br>
        </label>
        @error('email')
            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <label>
            Avatar <br>
            <input type="file" name="avatar" accept="image/*"> <br> 
        </label>
        @error('avatar')
            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <button class="button-login" type="submit">Sign Up</button>
        <a href="{{ route('auth.loginView') }}" class="login-link">Already have an account?</a> <hr>
        <a href="{{ route('auth.github.redirect') }}" class="github-link">
            <i class="fa-brands fa-github"></i> 
            <span style="margin-left: 20px">Use GitHub account</span>
        </a>
    </form>
@endsection