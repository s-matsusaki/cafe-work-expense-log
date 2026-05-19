@extends('layouts.app')

@section('title', '支出詳細')

@section('content')
    <h2>支出詳細</h2>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <dl>
        <dt>ID</dt>
        <dd>{{ $expense->id }}</dd>

        <dt>支出日</dt>
        <dd>{{ $expense->expense_date?->format('Y-m-d') }}</dd>

        <dt>支出内容</dt>
        <dd>{{ $expense->title }}</dd>

        <dt>金額</dt>
        <dd>{{ number_format($expense->amount) }}円</dd>

        <dt>支出種別</dt>
        <dd>{{ $expense->expense_type }}</dd>

        <dt>支払方法</dt>
        <dd>{{ $expense->payment_method ?? '未入力' }}</dd>

        <dt>関連カフェ</dt>
        <dd>{{ $expense->cafe?->name ?? '未設定' }}</dd>

        <dt>関連書籍</dt>
        <dd>{{ $expense->book?->title ?? '未設定' }}</dd>

        <dt>関連作業記録</dt>
        <dd>{{ $expense->workSession?->title ?? '未設定' }}</dd>

        <dt>会計ソフト記録済み</dt>
        <dd>{{ $expense->accounting_recorded ? '済' : '未' }}</dd>

        <dt>会計ソフト記録日時</dt>
        <dd>{{ $expense->accounting_recorded_at?->format('Y-m-d H:i') ?? '未設定' }}</dd>

        <dt>会計メモ</dt>
        <dd>{!! nl2br(e($expense->accounting_memo ?? '')) !!}</dd>

        <dt>メモ</dt>
        <dd>{!! nl2br(e($expense->memo ?? '')) !!}</dd>

        <dt>登録日時</dt>
        <dd>{{ $expense->created_at }}</dd>

        <dt>更新日時</dt>
        <dd>{{ $expense->updated_at }}</dd>
    </dl>

    <p>
        <a href="{{route('expenses.edit', $expense)}}">編集する</a>
    </p>

    <form action="{{ route('expenses.destroy', $expense) }}" method="POST">
        @csrf
        @method('DELETE')

        <button type="submit" onclick="return confirm('本当に削除しますか？')">
            削除する
        </button>
    </form>

    <p>
        <a href="{{ route('expenses.index') }}">一覧に戻る</a>
    </p>
@endsection