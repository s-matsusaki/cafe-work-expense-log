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
    <header class="bg-white border-b border-slate-200">
        <div class="mx-auto max-w-6xl px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold">
                        カフェログ
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">
                        作業・支出・書籍をまとめて管理
                    </p>
                </div>

                @auth
                    <div class="text-right">
                        <p class="text-sm text-slate-600">
                            ログイン中：{{ auth()->user()->name }}
                        </p>

                        <form action="{{ route('logout') }}" method="POST" class="mt-2">
                            @csrf
                            <button
                                type="submit"
                                class="rounded-md border border-slate-300 px-3 py-1.5 text-sm text-slate-700 hover:bg-slate-50"
                            >
                                ログアウト
                            </button>
                        </form>
                    </div>
                @endauth
            </div>

            @auth
                <nav class="mt-4 flex flex-wrap gap-2">
                    <a class="rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100" href="{{ route('dashboard') }}">トップ</a>
                    <a class="rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100" href="{{ route('cafes.index') }}">カフェ</a>
                    <a class="rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100" href="{{ route('books.index') }}">書籍</a>
                    <a class="rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100" href="{{ route('work-sessions.index') }}">作業記録</a>
                    <a class="rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100" href="{{ route('expenses.index') }}">支出</a>
                </nav>
            @else
                <nav class="mt-4 flex gap-2">
                    <a class="rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100" href="{{ route('login') }}">ログイン</a>
                    <a class="rounded-md bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700" href="{{ route('register') }}">ユーザー登録</a>
                </nav>
            @endauth
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-6 py-8">
        @if (session('status'))
            <div class="mb-6 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <p class="font-semibold">入力内容を確認してください。</p>
                <ul class="mt-2 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
