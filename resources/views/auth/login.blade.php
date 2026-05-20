@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
    <div class="mx-auto max-w-md">
        <div class="mb-6">
            <p class="text-sm font-medium text-blue-600">Login</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">ログイン</h2>
            <p class="mt-2 text-sm text-slate-500">カフェログにログインして、作業・支出・書籍を管理します。</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            @csrf

            <div class="grid gap-6">
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700">メールアドレス</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="you@example.com"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700">パスワード</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    >
                </div>

                <div>
                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-md bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
                    >
                        ログイン
                    </button>
                </div>
            </div>
        </form>

        @if (config('features.allow_user_registration'))
            <p class="mt-5 text-center text-sm text-slate-600">
                アカウントがない場合：
                <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline">ユーザー登録</a>
            </p>
        @endif
    </div>
@endsection
