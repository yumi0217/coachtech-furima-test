@extends('layouts.auth')

@section('title', 'メール認証')

@section('content')

<link rel="stylesheet" href="{{ asset('css/auth/verify.css') }}">

<div class="verify-container">
    <p>登録していただいたメールアドレスに認証メールを送信しました。</p>
    <p>メール認証を完了してください。</p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn">【認証はこちらから】</button>
    </form>

    <form method="POST" action="{{ route('verification.send') }}" class="resend-form">
        @csrf
        <button type="submit">認証メールを再送する</button>
    </form>
</div>
@endsection