<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'COACHTECH')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @yield('styles')
</head>

<body>
    <header class="header">
        <a href="{{ route('items.index') }}">
            <img src="{{ asset('images/logo.png') }}" alt="COACHTECHロゴ" class="logo">
        </a>


        <div class="search-box">
            <form method="GET" action="{{ route('items.index') }}">
                <!-- layouts.appのinputタグ -->
                <input
                    type="text"
                    name="keyword"
                    placeholder="なにをお探しですか？"
                    value="{{ old('keyword', $keyword ?? '') }}">

            </form>
        </div>


        <nav class="nav">
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                ログアウト
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="{{ route('mypage') }}">マイページ</a>
            <a href="{{ route('items.create') }}" class="upload-btn">出品</a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    @section('scripts')

    @stack('scripts')

    @yield('scripts')

</body>

</html>