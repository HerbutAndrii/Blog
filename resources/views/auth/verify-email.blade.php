@extends('layout')
@section('title', 'Verify Email')
@section('content')
    @include('header')
    <form class="auth-form" action="{{ route('verification.send') }}" method="POST">
        @csrf
        <h2>Verify Email</h2>
        @if(session('message'))
            <div style="text-align: center; color: #029af8; font-size: 20px">{{ session('message') }}</div>
        @endif
        <button class="button-send" type="submit">Resend Verification Link</button>
    </form>
@endsection