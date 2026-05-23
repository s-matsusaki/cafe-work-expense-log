@extends('layouts.app')

@section('title', '作業記録登録')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-blue-600">New Work Session</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">作業記録登録</h2>
            <p class="mt-2 text-sm text-slate-500">カフェでの作業内容と時間を登録します。</p>
        </div>

        <a
            href="{{ route('work-sessions.index') }}"
            class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
        >
            一覧に戻る
        </a>
    </div>

    <form action="{{ route('work-sessions.store') }}" method="POST" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        @csrf

        <div class="grid gap-6">
            <div>
                <label for="work_date" class="block text-sm font-semibold text-slate-700">作業日</label>
                <input
                    type="date"
                    id="work_date"
                    name="work_date"
                    value="{{ old('work_date', now()->toDateString()) }}"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
            </div>

            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700">作業タイトル</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title') }}"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    placeholder="例: Laravelの画面実装"
                >
            </div>

            @include('partials.cafe-select', [
                'label' => 'カフェ',
                'cafes' => $cafes,
                'name' => 'cafe_id',
                'id' => 'cafe_id',
                'emptyLabel' => '未選択',
                'selectedCafeId' => old('cafe_id'),
            ])

            <div class="grid gap-6 md:grid-cols-2">
                @include('partials.time-select', [
                    'name' => 'start_time',
                    'label' => '開始時刻',
                    'value' => null,
                ])

                @include('partials.time-select', [
                    'name' => 'end_time',
                    'label' => '終了時刻',
                    'value' => null,
                ])
            </div>

            <div>
                <label for="work_minutes" class="block text-sm font-semibold text-slate-700">作業時間（分）</label>
                <input
                    type="number"
                    id="work_minutes"
                    name="work_minutes"
                    min="0"
                    step="10"
                    value="{{ old('work_minutes') }}"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    placeholder="例: 120"
                >
            </div>

            <div>
                <label for="category" class="block text-sm font-semibold text-slate-700">カテゴリ</label>
                <input
                    type="text"
                    id="category"
                    name="category"
                    value="{{ old('category') }}"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    placeholder="例: 開発、学習、読書"
                >
            </div>

            <div>
                <label for="memo" class="block text-sm font-semibold text-slate-700">メモ</label>
                <textarea
                    name="memo"
                    id="memo"
                    rows="6"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    placeholder="例: 認証まわりのテストを追加。次回は一覧の絞り込みを確認する。"
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
