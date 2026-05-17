<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'カフェログ')</title>
</head>
<body>
    <header>
        <h1>カフェログ</h1>

        <nav>
            <a href="{{ route('cafes.index') }}">カフェ一覧</a>
            |
            <a href="{{ route('work-sessions.index') }}">作業記録一覧</a>
            |
            <a href="{{ route('expenses.index') }}">支出一覧</a>
        </nav>
    </header>
    
    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    @if ($errors->any())
        <div>
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