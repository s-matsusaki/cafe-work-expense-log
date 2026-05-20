@extends('layouts.app')

@section('title', '支出詳細')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-blue-600">Expense Detail</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">{{ $expense->title }}</h2>
            <p class="mt-2 text-sm text-slate-500">登録した支出の詳細情報です。</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a
                href="{{ route('expenses.edit', $expense) }}"
                class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
            >
                編集する
            </a>
            <a
                href="{{ route('expenses.index') }}"
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
                <dd class="text-sm text-slate-900 sm:col-span-2">#{{ $expense->id }}</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">支出日</dt>
                <dd class="text-sm text-slate-900 sm:col-span-2">{{ $expense->expense_date?->format('Y-m-d') }}</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">支出内容</dt>
                <dd class="text-sm font-medium text-slate-900 sm:col-span-2">{{ $expense->title }}</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">金額</dt>
                <dd class="text-sm text-slate-900 sm:col-span-2">{{ number_format($expense->amount) }}円</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">支出種別</dt>
                <dd class="text-sm text-slate-900 sm:col-span-2">{{ $expense->expense_type }}</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">支払方法</dt>
                <dd class="text-sm text-slate-900 sm:col-span-2">{{ $expense->payment_method ?? '未入力' }}</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">関連カフェ</dt>
                <dd class="text-sm text-slate-900 sm:col-span-2">{{ $expense->cafe?->name ?? '未設定' }}</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">関連書籍</dt>
                <dd class="text-sm text-slate-900 sm:col-span-2">{{ $expense->book?->title ?? '未設定' }}</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">関連作業記録</dt>
                <dd class="text-sm text-slate-900 sm:col-span-2">{{ $expense->workSession?->title ?? '未設定' }}</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">会計ソフト記録済み</dt>
                <dd class="text-sm text-slate-900 sm:col-span-2">{{ $expense->accounting_recorded ? '済' : '未' }}</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">会計ソフト記録日時</dt>
                <dd class="text-sm text-slate-900 sm:col-span-2">{{ $expense->accounting_recorded_at?->format('Y-m-d H:i') ?? '未設定' }}</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">会計メモ</dt>
                <dd class="whitespace-pre-line text-sm text-slate-900 sm:col-span-2">{{ $expense->accounting_memo ?: '未入力' }}</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">メモ</dt>
                <dd class="whitespace-pre-line text-sm text-slate-900 sm:col-span-2">{{ $expense->memo ?: '未入力' }}</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">登録日時</dt>
                <dd class="text-sm text-slate-900 sm:col-span-2">{{ $expense->created_at }}</dd>
            </div>

            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-semibold text-slate-500">更新日時</dt>
                <dd class="text-sm text-slate-900 sm:col-span-2">{{ $expense->updated_at }}</dd>
            </div>
        </dl>

        <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="flex justify-end">
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
