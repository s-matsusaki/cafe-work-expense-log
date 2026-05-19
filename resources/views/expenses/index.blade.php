@extends('layouts.app')

@section('title', '支出一覧')

@section('content')
    <h2>支出一覧</h2>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <p>
        <a href="{{ route('expenses.create') }}" class="button-link">支出を登録する</a>
    </p>

    <form action="{{ route('expenses.index') }}" method="GET">
        <div>
            <label for="accounting_recorded">会計記録</label>
            <select name="accounting_recorded" id="accounting_recorded">
                <option value="">すべて</option>
                <option value="0" @selected(request('accounting_recorded') === '0')>未記録</option>
                <option value="1" @selected(request('accounting_recorded') === '1')>記録済み</option>
            </select>
        </div>

        <div>
            <label for="expense_month">支出月</label>
            <input
                type="month"
                id="expense_month"
                name="expense_month"
                value="{{ request('expense_month') }}"
            >
            <small>例：2026-05</small>
        </div>

        @include('partials.expense-type-select', [
            'name' => 'expense_type',
            'id' => 'expense_type',
            'label' => '支出種別',
            'selected' => request('expense_type'),
            'showAllOption' => true,
            'showBreak' => false,
        ])

        <div>
            <button type="submit">絞り込む</button>
            <a href="{{ route('expenses.index') }}">条件をクリア</a>
        </div>
    </form>

    <section>
        <h3>集計</h3>

        <p>
            表示中の支出合計：
            <strong>{{ number_format($totalAmount) }}円</strong>

            <p>
                会計ソフト記録済み：
                {{ $recordedCount }}件
            </p>

            <p>
                会計ソフト未記載：
                {{ $unrecordedCount }}件
            </p>
        </p>
    </section>

    <hr>

    @if ($expenses->isEmpty())
        <p>登録されている支出はありません</p>
    @else
        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>支出日</th>
                    <th>内容</th>
                    <th>金額</th>
                    <th>種別</th>
                    <th>支払方法</th>
                    <th>カフェ</th>
                    <th>書籍</th>
                    <th>作業記録</th>
                    <th>会計記録済み</th>
                    <th>メモ</th>
                    <th>登録日時</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $expense)
                    <tr>
                        <td>{{ $expense->id }}</td>
                        <td>{{ $expense->expense_date?->format('Y-m-d') }}</td>
                        <td>{{ $expense->title }}</td>
                        <td>{{ number_format($expense->amount) }}</td>
                        <td>{{ $expense->expense_type }}</td>
                        <td>{{ $expense->payment_method ?? '未入力'}}</td>
                        <td>{{ $expense->cafe?->name ?? '未設定' }}</td>
                        <td>{{ $expense->book?->title ?? '未設定' }}</td>
                        <td>{{ $expense->workSession?->title ?? '未設定' }}</td>
                        <td>{{ $expense->accounting_recorded ? '済' : '未' }}</td>
                        <td>{{ $expense->memo }}</td>
                        <td>{{ $expense->created_at }}</td>
                        <td>
                            <a href="{{ route('expenses.show', $expense) }}">詳細</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection