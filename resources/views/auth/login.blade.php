@extends('layout')
@section('title', 'Login')
@section('content')
    <form class="auth-form" action="{{ route('auth.login') }}" method="POST">
        @csrf
        <h2>Log In</h2>
        @if(session('status'))
            <div style="color: #029af8; font-size: 20px; text-align: center; margin-bottom: 20px" >{{ session('status') }}</div>
        @endif
        @error('login')
            <div style="color: red; font-size: 20px; text-align: center; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <label>
            Email <br>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email"> <br>
        </label>
        @error('email')
            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <label>
            Password 
            <a href="{{ route('password.request') }}" class="forgot-password-link">Forgot your password?</a> <br>
            <input type="password" name="password" value="{{ old('password') }}" placeholder="Enter your password"> <br>
        </label>
        @error('password')
            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <div style="display: flex">
            <label>
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : ''}}>
                Remember me
            </label>
        </div>
        <button class="button-login" type="submit">Log In</button>
        <a href="{{ route('auth.registerView') }}" class="register-link">You do not have an account?</a> <hr>
        <a href="{{ route('auth.github.redirect') }}" class="github-link">
            <i class="fa-brands fa-github"></i> 
            <span style="margin-left: 20px">Use GitHub account</span>
        </a>
    </form>
@endsection