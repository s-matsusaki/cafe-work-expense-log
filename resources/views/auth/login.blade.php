@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
    <h2>ログイン</h2>

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div>
            <label for="email">メールアドレス</label><br>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
            >
        </div>

        <div>
            <label for="password">パスワード</label><br>
            <input
                type="password"
                id="password"
                name="password"
            >
        </div>

        <div>
            <button type="submit">ログイン</button>
        </div>
    </form>

    <p>
        アカウントがない場合：
        <a href="{{ route('register') }}" class="button-link">ユーザー登録</a>
    </p>
@endsection