@extends('layouts.app')

@section('title', 'ユーザー登録')

@section('content')
    <h2>ユーザー登録</h2>

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <div>
            <label for="name">名前</label><br>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
            >
        </div>

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
            <label for="password_confirmation">パスワード確認</label><br>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
            >
        </div>

        <div>
            <button type="submit">登録する</button>
        </div>

        <p>
            すでにアカウントがある場合：
            <a href="{{ route('login') }}" class="button-link">ログイン</a>
        </p>
    </form>
@endsection