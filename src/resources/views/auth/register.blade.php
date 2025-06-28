@extends('layouts.auth')

@section('title', '会員登録')

@section('content')
<div class="register-wrapper">
    <div class="register-container">
        <h2 class="form-title">会員登録</h2>

        <form method="POST" action="{{ route('register.post') }}" novalidate>
            @csrf

            <div class="form-group">
                <label for="name">ユーザ名</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}">
                @error('name')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="text" name="email" id="email" value="{{ old('email') }}">
                @error('email')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" name="password" id="password">
                @error('password')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">確認用パスワード</label>
                <input type="password" name="password_confirmation" id="password_confirmation">
                {{-- password_confirmation に対する個別エラーメッセージは出ませんが、password.confirmed のエラーとして出ます --}}
            </div>

            <div class="form-button">
                <button type="submit">登録する</button>
            </div>
        </form>

        <div class="login-link">
            <p><a href="{{ route('login') }}">ログインはこちら</a></p>
        </div>
    </div>
</div>
@endsection