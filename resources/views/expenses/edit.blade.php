@extends('layouts.app')

@section('title', '支出編集')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-blue-600">Edit Expense</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">支出編集</h2>
            <p class="mt-2 text-sm text-slate-500">{{ $expense->title }} の情報を更新します。</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a
                href="{{ route('expenses.show', $expense) }}"
                class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
            >
                詳細に戻る
            </a>
            <a
                href="{{ route('expenses.index') }}"
                class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
            >
                一覧に戻る
            </a>
        </div>
    </div>

    <form action="{{ route('expenses.update', $expense) }}" method="POST" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        @method('PUT')

        <div class="grid gap-6">
            <div>
                <label for="expense_date" class="block text-sm font-semibold text-slate-700">支出日</label>
                <input
                    type="date"
                    id="expense_date"
                    name="expense_date"
                    value="{{ old('expense_date', $expense->expense_date?->format('Y-m-d')) }}"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
            </div>

            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700">支出内容</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title', $expense->title) }}"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    placeholder="例: コーヒー代"
                >
            </div>

            <div>
                <label for="amount" class="block text-sm font-semibold text-slate-700">金額</label>
                <input
                    type="text"
                    id="amount"
                    name="amount"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    value="{{ old('amount', $expense->amount) }}"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    placeholder="例: 580"
                >
            </div>

            @include('partials.expense-type-select', [
                'name' => 'expense_type',
                'id' => 'expense_type',
                'label' => '支出種別',
                'selected' => old('expense_type', $expense->expense_type),
                'showAllOption' => false,
            ])

            <div>
                <label for="payment_method" class="block text-sm font-semibold text-slate-700">支払方法</label>
                <select
                    id="payment_method"
                    name="payment_method"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
                    <option value="">未選択</option>
                    <option value="cash" @selected(old('payment_method', $expense->payment_method) === 'cash')>現金</option>
                    <option value="card" @selected(old('payment_method', $expense->payment_method) === 'card')>カード</option>
                    <option value="qr" @selected(old('payment_method', $expense->payment_method) === 'qr')>QR決済</option>
                    <option value="other" @selected(old('payment_method', $expense->payment_method) === 'other')>その他</option>
                </select>
            </div>

            @include('partials.cafe-select', [
                'label' => '利用場所',
                'cafes' => $cafes,
                'name' => 'cafe_id',
                'id' => 'cafe_id',
                'emptyLabel' => '未選択',
                'selectedCafeId' => old('cafe_id', $expense->cafe_id),
            ])

            @include('partials.book-select', [
                'books' => $books,
                'selectedBookId' => old('book_id', $expense->book_id),
            ])

            <div>
                <label for="work_session_id" class="block text-sm font-semibold text-slate-700">関連作業記録</label>
                <select
                    name="work_session_id"
                    id="work_session_id"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
                    <option value="">未選択</option>
                    @foreach ($workSessions as $workSession)
                        <option
                            value="{{ $workSession->id }}"
                            @selected((string) old('work_session_id', $expense->work_session_id) === (string) $workSession->id)
                        >
                            {{ $workSession->work_date_label }}
                            {{ $workSession->time_range_label }}
                            -
                            {{ $workSession->title }}
                            @if ($workSession->cafe)
                                （{{ $workSession->cafe->name }}）
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="rounded-md border border-slate-200 bg-slate-50 px-4 py-3">
                <label class="flex items-center gap-3 text-sm font-semibold text-slate-700">
                    <input
                        type="checkbox"
                        name="accounting_recorded"
                        value="1"
                        @checked(old('accounting_recorded', $expense->accounting_recorded))
                        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                    >
                    会計ソフトに記録済み
                </label>
            </div>

            <div>
                <label for="accounting_recorded_at" class="block text-sm font-semibold text-slate-700">会計ソフト記録日時</label>
                <input
                    type="datetime-local"
                    id="accounting_recorded_at"
                    name="accounting_recorded_at"
                    value="{{ old('accounting_recorded_at', $expense->accounting_recorded_at?->format('Y-m-d\TH:i')) }}"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
            </div>

            <div>
                <label for="accounting_memo" class="block text-sm font-semibold text-slate-700">会計メモ</label>
                <textarea
                    name="accounting_memo"
                    id="accounting_memo"
                    rows="5"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    placeholder="例: freeeに登録済み。領収書は写真で保存。"
                >{{ old('accounting_memo', $expense->accounting_memo) }}</textarea>
            </div>

            <div>
                <label for="memo" class="block text-sm font-semibold text-slate-700">メモ</label>
                <textarea
                    name="memo"
                    id="memo"
                    rows="6"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    placeholder="例: 天神地下街店で作業。レシートあり。"
                >{{ old('memo', $expense->memo) }}</textarea>
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-md bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
                >
                    更新する
                </button>
            </div>
        </div>
    </form>
@endsection
