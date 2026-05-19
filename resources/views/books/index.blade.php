@extends('layouts.app')

@section('title', '書籍一覧')

@section('content')
    <h2>書籍一覧</h2>

    <p>
        <a href="{{ route('books.create') }}">書籍を登録する</a>
    </p>

    @if ($books->isEmpty())
        <p>登録されている書籍はありません。</p>
    @else
        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>タイトル</th>
                    <th>購入日</th>
                    <th>状態</th>
                    <th>メモ</th>
                    <th>登録日時</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $book)
                    <tr>
                        <td>{{ $book->id }}</td>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->purchased_on?->format('Y-m-d') ?? '未入力' }}</td>
                        <td>{{ $book->status ?? '未入力' }}</td>
                        <td>{{ $book->memo }}</td>
                        <td>{{ $book->created_at }}</td>
                        <td>
                            <a href="{{ route('books.show', $book) }}">詳細</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection