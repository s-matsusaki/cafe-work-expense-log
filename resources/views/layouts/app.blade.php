<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title', 'カフェログ')</title>
</head>
<body class="bg-slate-100 text-slate-900">
    <header>
        <h1>カフェログ</h1>

        <div>
            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <span>ログイン中：{{ auth()->user()->name }}</span>
                    <button type="submit">ログアウト</button>
                </form>        
            @else
                <p>
                    <a href="{{ route('login') }}">ログイン</a>
                    |
                    <a href="{{ route('register') }}">ユーザー登録</a>
                </p>
            @endauth
        </div>

        @auth
            <nav>
                <a href="{{ route('dashboard') }}" class="button-link">トップ</a>
                |
                <a href="{{ route('cafes.index') }}" class="button-link">カフェ一覧</a>
                |
                <a href="{{ route('work-sessions.index') }}" class="button-link">作業記録一覧</a>
                |
                <a href="{{ route('books.index') }}" class="button-link">書籍一覧</a>
                |
                <a href="{{ route('expenses.index') }}" class="button-link">支出一覧</a>
            </nav>
        @endauth
    </header>
    
    @if (session('status'))
        <div class="status-message">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="error-message">
            <p>入力内容を確認してください</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <main>
        @yield('content')
    </main>
</body>
</html>