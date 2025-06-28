@extends('layouts.app')

@section('title', 'プロフィール設定')

@section('content')
<link rel="stylesheet" href="{{ asset('css/profiles/edit.css') }}">

<div class="container">
    <h1 class="profile-title">プロフィール設定</h1>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if (session('status'))
        <div class="success-message">
            {{ session('status') }}
        </div>
        @endif

        <!-- プロフィール画像表示 -->
        <div class="profile-image-wrapper">
            <label for="profile_image" class="profile-image-label">
                <img id="preview"
                    src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/sample-profile.png') }}"
                    class="profile-image">
            </label>

            <input type="file" name="profile_image" id="profile_image" style="display: none;">
            <label for="profile_image" class="image-select-btn">画像を選択する</label>

            @error('profile_image')
            <div class="error-message">{{ $message }}</div>
            @enderror


        </div>

        <!-- 入力フォーム -->
        <!-- ユーザー名 -->
        <label class="form-label" for="username">ユーザー名</label>
        <input type="text" id="username" name="username" class="form-input" value="{{ old('username', $user->profile->username ?? '') }}">
        @error('username')
        <div class="error-message">{{ $message }}</div>
        @enderror

        <!-- 郵便番号 -->
        <label class="form-label" for="postal_code">郵便番号</label>
        <input type="text" id="postal_code" name="postal_code" class="form-input" value="{{ old('postal_code', $user->profile->postal_code ?? '') }}">
        @error('postal_code')
        <div class="error-message">{{ $message }}</div>
        @enderror

        <!-- 住所 -->
        <label class="form-label" for="address">住所</label>
        <input type="text" id="address" name="address" class="form-input" value="{{ old('address', $user->profile->address ?? '') }}">
        @error('address')
        <div class="error-message">{{ $message }}</div>
        @enderror

        <!-- 建物名 -->
        <label class="form-label" for="building">建物名</label>
        <input type="text" id="building" name="building" class="form-input" value="{{ old('building', $user->profile->building ?? '') }}">
        @error('building')
        <div class="error-message">{{ $message }}</div>
        @enderror



        <button type="submit" class="update-btn">更新する</button>
    </form>
</div>

<!-- プレビュー表示のためのJS（インストール不要） -->
<script>
    document.getElementById('profile_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const allowedTypes = ['image/jpeg', 'image/png'];

        if (file) {
            if (!allowedTypes.includes(file.type)) {
                alert('プロフィール画像は.jpegまたは.png形式にしてください。');
                e.target.value = ''; // ファイル選択をリセット
                document.getElementById('preview').src = "{{ asset('images/sample-profile.png') }}"; // プレビューもリセット
                return;
            }

            // プレビュー表示（形式がOKな場合）
            const preview = document.getElementById('preview');
            preview.src = URL.createObjectURL(file);
        }
    });
</script>

@endsection