@extends('layouts.app')

@section('title', '書籍詳細')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-blue-600">Book Detail</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">{{ $book->title }}</h2>
            <p class="mt-2 text-sm text-slate-500">登録した書籍の詳細情報です。</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a
                href="{{ route('books.edit', $book) }}"
                class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
            >
                編集する
            </a>
            <a
                href="{{ route('books.index') }}"
                class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
            >
                一覧に戻る
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <dl class="divide-y divide-slate-100">
            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">ID</dt>
                <dd class="text-sm text-slate-900 sm:col-span-2">#{{ $book->id }}</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">タイトル</dt>
                <dd class="text-sm font-medium text-slate-900 sm:col-span-2">{{ $book->title }}</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">購入日</dt>
                <dd class="text-sm text-slate-900 sm:col-span-2">{{ $book->purchased_on?->format('Y-m-d') ?? '未入力' }}</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">状態</dt>
                <dd class="text-sm text-slate-900 sm:col-span-2">{{ $book->status ?? '未入力' }}</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">メモ</dt>
                <dd class="whitespace-pre-line text-sm text-slate-900 sm:col-span-2">{{ $book->memo ?: '未入力' }}</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">登録日時</dt>
                <dd class="text-sm text-slate-900 sm:col-span-2">{{ $book->created_at }}</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">更新日時</dt>
                <dd class="text-sm text-slate-900 sm:col-span-2">{{ $book->updated_at }}</dd>
            </div>
        </dl>

        <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
            <form action="{{ route('books.destroy', $book) }}" method="POST" class="flex justify-end">
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    onclick="return confirm('本当に削除しますか？')"
                    class="inline-flex items-center justify-center rounded-md border border-red-300 bg-white px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-50"
                >
                    削除する
                </button>
            </form>
        </div>
    </div>
@endsection
