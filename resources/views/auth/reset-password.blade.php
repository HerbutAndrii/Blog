@extends('layout')
@section('title', 'Reset Password')
@section('content')
    <form class="auth-form" action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $request->token }}">
        <h2>Reset password</h2>
        <label>
            Email <br>
            <input type="email" name="email" value="{{ old('email', $request->email) }}" placeholder="Enter your email"> <br>
        </label>
        @error('email')
            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <label>
            Password 
            <input type="password" name="password" placeholder="Enter new password"> <br>
        </label>
        @error('password')
            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <label>
            Confirm password 
            <input type="password" name="password_confirmation" placeholder="Confirm your password"> <br>
        </label>
        <button class="button-reset" type="submit">Reset</button>
    </form>
@endsection