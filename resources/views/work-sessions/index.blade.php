@extends('layouts.app')

@section('title', '作業記録一覧')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-blue-600">Work Sessions</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">作業記録一覧</h2>
            <p class="mt-2 text-sm text-slate-500">
                作業場所ごとの作業時間や内容を管理します。
            </p>
        </div>

        <a
            href="{{ route('work-sessions.create') }}"
            class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
        >
            作業記録を登録する
        </a>
    </div>

    <form action="{{ route('work-sessions.index') }}" method="GET" class="mb-6 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <label for="work_month" class="block text-sm font-semibold text-slate-700">作業月</label>
                <input
                    type="month"
                    id="work_month"
                    name="work_month"
                    value="{{ request('work_month') }}"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
                <p class="mt-1 text-xs text-slate-500">例: 2026-05</p>
            </div>

            @include('partials.cafe-select', [
                'label' => '作業場所',
                'cafes' => $cafes,
                'name' => 'cafe_id',
                'id' => 'cafe_id',
                'emptyLabel' => 'すべて',
            ])

            <div>
                <label for="category" class="block text-sm font-semibold text-slate-700">カテゴリ</label>
                <select
                    name="category"
                    id="category"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
                    <option value="">すべて</option>
                    @foreach ($categories as $category)
                        <option
                            value="{{ $category }}"
                            @selected(request('category') === $category)
                        >
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-5 flex flex-wrap justify-end gap-2">
            <a
                href="{{ route('work-sessions.index') }}"
                class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                条件をクリア
            </a>
            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
            >
                絞り込む
            </button>
        </div>
    </form>

    <section class="mb-6 grid gap-4 md:grid-cols-3">
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">表示中の作業時間合計</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($totalWorkMinutes) }}分</p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">時間換算</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ intdiv($totalWorkMinutes, 60) }}時間{{ $totalWorkMinutes % 60 }}分</p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">表示件数</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $workSessions->count() }}件</p>
        </div>
    </section>

    @if ($workSessions->isEmpty())
        <div class="rounded-lg border border-dashed border-slate-300 bg-white px-6 py-12 text-center shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">登録されている作業記録はありません</h3>
            <p class="mt-2 text-sm text-slate-500">最初の作業記録を登録して、場所ごとの作業時間を見える化しましょう。</p>
            <a
                href="{{ route('work-sessions.create') }}"
                class="mt-5 inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
            >
                作業記録を登録する
            </a>
        </div>
    @else
        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">作業日</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">タイトル</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">作業場所</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">時間帯</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">作業時間</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">カテゴリ</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">操作</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($workSessions as $workSession)
                            <tr class="hover:bg-slate-50">
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">#{{ $workSession->id }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">{{ $workSession->work_date_label }}</td>
                                <td class="px-4 py-4 text-sm font-semibold text-slate-900">{{ $workSession->title }}</td>
                                <td class="px-4 py-4 text-sm text-slate-700">{{ $workSession->cafe?->name ?? '未設定' }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">{{ $workSession->time_range_label }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">
                                    @if (!is_null($workSession->work_minutes))
                                        {{ $workSession->work_minutes }}分
                                    @else
                                        未入力
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">{{ $workSession->category ?? '未入力' }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-right text-sm">
                                    <a
                                        href="{{ route('work-sessions.show', $workSession) }}"
                                        class="inline-flex items-center justify-center rounded-md border border-slate-300 px-3 py-1.5 font-medium text-slate-700 hover:bg-slate-50"
                                    >
                                        詳細
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
