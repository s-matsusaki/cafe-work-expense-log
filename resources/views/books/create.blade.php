@extends('layouts.app')

@section('title', '書籍登録')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-blue-600">New Book</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">書籍登録</h2>
            <p class="mt-2 text-sm text-slate-500">購入した書籍や読書状況を登録します。</p>
        </div>

        <a
            href="{{ route('books.index') }}"
            class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
        >
            一覧に戻る
        </a>
    </div>

    <form action="{{ route('books.store') }}" method="POST" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        @csrf

        <div class="grid gap-6">
            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700">タイトル</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title') }}"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    placeholder="例: リーダブルコード"
                >
            </div>

            <div>
                <label for="purchased_on" class="block text-sm font-semibold text-slate-700">購入日</label>
                <input
                    type="date"
                    id="purchased_on"
                    name="purchased_on"
                    value="{{ old('purchased_on') }}"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
            </div>

            <div>
                <label for="status" class="block text-sm font-semibold text-slate-700">状態</label>
                <select
                    id="status"
                    name="status"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
                    <option value="">未選択</option>
                    <option value="unread" @selected(old('status') === 'unread')>未読</option>
                    <option value="reading" @selected(old('status') === 'reading')>読書中</option>
                    <option value="done" @selected(old('status') === 'done')>読了</option>
                    <option value="paused" @selected(old('status') === 'paused')>中断</option>
                </select>
            </div>

            <div>
                <label for="memo" class="block text-sm font-semibold text-slate-700">メモ</label>
                <textarea
                    id="memo"
                    name="memo"
                    rows="6"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    placeholder="例: 設計や命名の考え方を作業前に読み返したい。"
                >{{ old('memo') }}</textarea>
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-md bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
                >
                    登録する
                </button>
            </div>
        </div>
    </form>
@endsection
