<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
    @stack('styles')
</head>

<body>
    <header class="auth-header">
        <div class="auth-logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="CoachTech" height="60">
            </a>
        </div>
    </header>

    <div class="auth-container">
        @yield('content')
    </div>
</body>

</html>