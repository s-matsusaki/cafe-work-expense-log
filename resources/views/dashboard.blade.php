@extends('layouts.app')

@section('title', 'ダッシュボード')

@section('content')
    <div class="mb-6">
        <p class="text-sm font-medium text-blue-600">Dashboard</p>
        <h2 class="mt-1 text-2xl font-bold text-slate-900">ダッシュボード</h2>
        <p class="mt-2 text-sm text-slate-500">{{ $currentMonth }} の作業・支出・書籍の状況です。</p>
    </div>

    <section class="mb-8 grid gap-4 md:grid-cols-2 lg:grid-cols-5">
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">今月の作業時間</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($monthlyWorkMinutes) }}分</p>
            <p class="mt-1 text-xs text-slate-500">{{ intdiv($monthlyWorkMinutes, 60) }}時間{{ $monthlyWorkMinutes % 60 }}分</p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">今月の支出合計</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($monthlyExpenseAmount) }}円</p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">会計ソフト未記録</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $unrecordedExpenseCount }}件</p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">読書中の書籍</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $readingBookCount }}冊</p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">未読の書籍</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $unreadBookCount }}冊</p>
        </div>
    </section>

    <section class="mb-8">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-900">メニュー</h3>
                <p class="mt-1 text-sm text-slate-500">よく使う画面へ移動します。</p>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('cafes.index') }}" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm hover:border-blue-200 hover:bg-blue-50">
                <p class="text-sm font-medium text-blue-600">Cafes</p>
                <p class="mt-1 text-base font-semibold text-slate-900">カフェ一覧</p>
            </a>

            <a href="{{ route('books.index') }}" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm hover:border-blue-200 hover:bg-blue-50">
                <p class="text-sm font-medium text-blue-600">Books</p>
                <p class="mt-1 text-base font-semibold text-slate-900">書籍一覧</p>
            </a>

            <a href="{{ route('work-sessions.index') }}" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm hover:border-blue-200 hover:bg-blue-50">
                <p class="text-sm font-medium text-blue-600">Work Sessions</p>
                <p class="mt-1 text-base font-semibold text-slate-900">作業記録一覧</p>
            </a>

            <a href="{{ route('expenses.index') }}" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm hover:border-blue-200 hover:bg-blue-50">
                <p class="text-sm font-medium text-blue-600">Expenses</p>
                <p class="mt-1 text-base font-semibold text-slate-900">支出一覧</p>
            </a>
        </div>
    </section>

    <section class="mb-8">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-900">直近の作業記録</h3>
                <p class="mt-1 text-sm text-slate-500">最近登録した作業ログです。</p>
            </div>
            <a
                href="{{ route('work-sessions.index') }}"
                class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                一覧を見る
            </a>
        </div>

        @if ($recentWorkSessions->isEmpty())
            <div class="rounded-lg border border-dashed border-slate-300 bg-white px-6 py-10 text-center shadow-sm">
                <p class="text-sm font-semibold text-slate-900">作業記録はまだありません</p>
            </div>
        @else
            <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">作業日</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">タイトル</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">カフェ</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">作業時間</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">操作</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($recentWorkSessions as $workSession)
                                <tr class="hover:bg-slate-50">
                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">{{ $workSession->work_date_label }}</td>
                                    <td class="px-4 py-4 text-sm font-semibold text-slate-900">{{ $workSession->title }}</td>
                                    <td class="px-4 py-4 text-sm text-slate-700">{{ $workSession->cafe?->name ?? '未設定' }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">
                                        @if (!is_null($workSession->work_minutes))
                                            {{ $workSession->work_minutes }}分
                                        @else
                                            未入力
                                        @endif
                                    </td>
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
    </section>

    <section class="mb-8">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-900">直近の支出</h3>
                <p class="mt-1 text-sm text-slate-500">最近登録した支出記録です。</p>
            </div>
            <a
                href="{{ route('expenses.index') }}"
                class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                一覧を見る
            </a>
        </div>

        @if ($recentExpenses->isEmpty())
            <div class="rounded-lg border border-dashed border-slate-300 bg-white px-6 py-10 text-center shadow-sm">
                <p class="text-sm font-semibold text-slate-900">支出記録はまだありません</p>
            </div>
        @else
            <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">支出日</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">内容</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">金額</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">種別</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">会計記録</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">操作</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($recentExpenses as $expense)
                                <tr class="hover:bg-slate-50">
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
    </section>

    <section>
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-900">直近の書籍</h3>
                <p class="mt-1 text-sm text-slate-500">最近登録した書籍です。</p>
            </div>
            <a
                href="{{ route('books.index') }}"
                class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                一覧を見る
            </a>
        </div>

        @if ($recentBooks->isEmpty())
            <div class="rounded-lg border border-dashed border-slate-300 bg-white px-6 py-10 text-center shadow-sm">
                <p class="text-sm font-semibold text-slate-900">書籍記録はまだありません</p>
            </div>
        @else
            <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">タイトル</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">購入日</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">状態</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">操作</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($recentBooks as $book)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-4 text-sm font-semibold text-slate-900">{{ $book->title }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">{{ $book->purchased_on?->format('Y-m-d') ?? '未入力' }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">{{ $book->status ?? '未入力' }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-right text-sm">
                                        <a
                                            href="{{ route('books.show', $book) }}"
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
    </section>
@endsection
