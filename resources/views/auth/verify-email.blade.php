@extends('layout')
@section('title', 'Verify Email')
@include('header')
@section('content')
    <form class="auth-form" action="{{ route('verification.send') }}" method="POST">
        @csrf
        <h2>Verify Email</h2>
        @if(session('message'))
            <div style="text-align: center; color: #029af8; font-size: 20px">{{ session('message') }}</div>
        @endif
        <button class="button-resend" type="submit">Resend Verification Link</button>
    </form>
@endsection