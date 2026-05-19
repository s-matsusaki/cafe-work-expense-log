@extends('layouts.app')

@section('title', '書籍編集')

@section('content')
    <h2>書籍編集</h2>

    <form action="{{ route('books.update', $book) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="title">タイトル</label><br>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title', $book->title) }}"
            >
        </div>

        <div>
            <label for="purchased_on">購入日</label><br>
            <input
                type="date"
                id="purchased_on"
                name="purchased_on"
                value="{{ old('purchased_on', $book->purchased_on?->format('Y-m-d')) }}"
            >
        </div>

        <div>
            <label for="status">状態</label><br>
            <select id="status" name="status">
                <option value="">未選択</option>
                <option value="unread" @selected(old('status', $book->status) === 'unread')>未読</option>
                <option value="reading" @selected(old('status', $book->status) === 'reading')>読書中</option>
                <option value="done" @selected(old('status', $book->status) === 'done')>読了</option>
                <option value="paused" @selected(old('status', $book->status) === 'paused')>中断</option>
            </select>
        </div>

        <div>
            <label for="memo">メモ</label><br>
            <textarea id="memo" name="memo">{{ old('memo', $book->memo) }}</textarea>
        </div>

        <div>
            <button type="submit">更新する</button>
        </div>
    </form>

    <p>
        <a href="{{ route('books.show', $book) }}" class="button-link">詳細に戻る</a>
    </p>

    <p>
        <a href="{{ route('books.index') }}" class="button-link">一覧に戻る</a>
    </p>
@endsection