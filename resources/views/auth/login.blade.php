@extends('layout')
@section('title', 'Login')
@section('content')
    <form class="auth-form" action="{{ route('auth.login') }}" method="POST">
        @csrf
        <h2>Log in</h2>
        @error('Login')
            <div style="color: red; font-size: 20px; text-align: center" >{{ $message }}</div>
        @enderror
        <label>
            Password <br>
            <input type="password" name="password" value="{{ old('password') }}" placeholder="Enter your password"> <br>
        </label>
        @error('password')
            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <label>
            Email <br>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email"> <br>
        </label>
        @error('email')
            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <button class="button-login" type="submit">Login</button>
        <a href="{{ route('auth.registerView') }}">You do not have an account?</a>
    </form>
@endsection