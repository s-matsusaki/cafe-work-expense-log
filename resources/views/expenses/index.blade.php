@extends('layouts.app')

@section('title', '支出一覧')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-blue-600">Expenses</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">支出一覧</h2>
            <p class="mt-2 text-sm text-slate-500">
                カフェ代や書籍代など、作業に関わる支出を管理します。
            </p>
        </div>

        <a
            href="{{ route('expenses.create') }}"
            class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
        >
            支出を登録する
        </a>
    </div>

    <form action="{{ route('expenses.index') }}" method="GET" class="mb-6 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <label for="accounting_recorded" class="block text-sm font-semibold text-slate-700">会計記録</label>
                <select
                    name="accounting_recorded"
                    id="accounting_recorded"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
                    <option value="">すべて</option>
                    <option value="0" @selected(request('accounting_recorded') === '0')>未記録</option>
                    <option value="1" @selected(request('accounting_recorded') === '1')>記録済み</option>
                </select>
            </div>

            <div>
                <label for="expense_month" class="block text-sm font-semibold text-slate-700">支出月</label>
                <input
                    type="month"
                    id="expense_month"
                    name="expense_month"
                    value="{{ request('expense_month') }}"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
                <p class="mt-1 text-xs text-slate-500">例: 2026-05</p>
            </div>

            @include('partials.expense-type-select', [
                'name' => 'expense_type',
                'id' => 'expense_type',
                'label' => '支出種別',
                'selected' => request('expense_type'),
                'showAllOption' => true,
            ])
        </div>

        <div class="mt-5 flex flex-wrap justify-end gap-2">
            <a
                href="{{ route('expenses.index') }}"
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
            <p class="text-sm font-medium text-slate-500">表示中の支出合計</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($totalAmount) }}円</p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">会計ソフト記録済み</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $recordedCount }}件</p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">会計ソフト未記載</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $unrecordedCount }}件</p>
        </div>
    </section>

    @if ($expenses->isEmpty())
        <div class="rounded-lg border border-dashed border-slate-300 bg-white px-6 py-12 text-center shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">登録されている支出はありません</h3>
            <p class="mt-2 text-sm text-slate-500">最初の支出を登録して、作業にかかった費用を記録しましょう。</p>
            <a
                href="{{ route('expenses.create') }}"
                class="mt-5 inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
            >
                支出を登録する
            </a>
        </div>
    @else
        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">支出日</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">内容</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">金額</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">種別</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">会計記録</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">操作</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($expenses as $expense)
                            <tr class="hover:bg-slate-50">
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">#{{ $expense->id }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">{{ $expense->expense_date_label }}</td>
                                <td class="px-4 py-4 text-sm font-semibold text-slate-900">{{ $expense->title }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">{{ number_format($expense->amount) }}円</td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">{{ $expense->expense_type }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">{{ $expense->accounting_recorded ? '済' : '未' }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-right text-sm">
                                    <a
                                        href="{{ route('expenses.show', $expense) }}"
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
