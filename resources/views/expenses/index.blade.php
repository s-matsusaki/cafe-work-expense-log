@extends('layouts.app')

@section('title', '支出一覧')

@section('content')
    <h2>支出一覧</h2>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <p>
        <a href="{{ route('expenses.create') }}">支出を登録する</a>
    </p>

    <p>
        <a href="{{ route('cafes.index') }}">カフェ一覧へ</a>
        |
        <a href="{{ route('work-sessions.index') }}">作業記録一覧へ</a>
    </p>

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