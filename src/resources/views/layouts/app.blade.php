<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')
</head>
<body>
    <header class="header">
        <div class="header-inner">
            <div class="header-logo">
                <a href="/">
                    <img src="{{ asset('img/headerlogo.png') }}" alt="COACHTECH">
                </a>
            </div>
            <div class="header-search">
                <form action="/" method="GET">
                    <input type="hidden" name="tab" value="{{ request('tab', 'index') }}">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？">
                </form>
            </div>
            <nav class="header-nav">
                <ul>
                    @auth
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                                @csrf
                                <button type="submit" class="nav-link logout-button">ログアウト</button>
                            </form>
                        </li>
                        <li><a href="/mypage" class="nav-link">マイページ</a></li>
                        <li><a href="/sell" class="btn-sell">出品</a></li>
                    @endauth
                    @guest
                        <li><a href="/login" class="nav-link">ログイン</a></li>
                        <li><a href="/register" class="nav-link">会員登録</a></li>
                        <li><a href="/sell" class="btn-sell">出品</a></li>
                    @endguest
                </ul>
            </nav>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>