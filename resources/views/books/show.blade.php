@extends('layouts.app')

@section('title', '書籍詳細')

@section('content')
    <h2>書籍詳細</h2>

    <dl>
        <dt>ID</dt>
        <dd>{{ $book->id }}</dd>

        <dt>タイトル</dt>
        <dd>{{ $book->title }}</dd>

        <dt>購入日</dt>
        <dd>{{ $book->purchased_on?->format('Y-m-d') ?? '未入力' }}</dd>

        <dt>状態</dt>
        <dd>{{ $book->status ?? '未入力' }}</dd>

        <dt>メモ</dt>
        <dd>{!! nl2br(e($book->memo ?? '')) !!}</dd>

        <dt>登録日時</dt>
        <dd>{{ $book->created_at }}</dd>

        <dt>更新日時</dt>
        <dd>{{ $book->updated_at }}</dd>
    </dl>

    <p>
        <a href="{{ route('books.edit', $book) }}">編集する</a>
    </p>

    <form action="{{ route('books.destroy', $book) }}" method="POST">
        @csrf
        @method('DELETE')

        <button type="submit" onclick="return confirm('本当に削除しますか？')">
            削除する
        </button>
    </form>

    <p>
        <a href="{{ route('books.index') }}">一覧に戻る</a>
    </p>
@endsection