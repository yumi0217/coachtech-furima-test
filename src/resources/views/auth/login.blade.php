@extends('layouts.auth')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endpush

@section('content')
<div class="login-container">
    <h1 class="form-title">ログイン</h1>

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email">
            @if ($errors->has('email'))
            <div style="color: red;">{{ $errors->first('email') }}</div>
            @endif
        </div>

        <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password">
            @if ($errors->has('password'))
            <div style="color: red;">{{ $errors->first('password') }}</div>
            @endif
        </div>

        <div class="form-button">
            <button type="submit">ログインする</button>
        </div>
    </form>

    <div class="register-link">
        <a href="{{ route('register') }}">会員登録はこちら</a>
    </div>
</div>
@endsection