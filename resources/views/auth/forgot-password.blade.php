@extends('layout')
@section('title', 'Forgot Password')
@section('content')
    <form class="auth-form" action="{{ route('password.email') }}" method="POST">
        @csrf
        <h2>Forgot password?</h2>
        @if(session('status'))
            <div style="text-align: center; color: #029af8; font-size: 20px">{{ session('status') }}</div>
        @endif
        <label>
            Email
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email"> <br>
        </label>
        @error('email')
            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <button class="button-send" type="submit">Send Password Reset Link</button>
    </form>
@endsection