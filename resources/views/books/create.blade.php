@extends('layouts.app')

@section('title', '書籍登録')

@section('content')
    <h2>書籍登録</h2>

    <form action="{{ route('books.store') }}" method="POST">
        @csrf

        <div>
            <label for="title">タイトル</label><br>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title') }}"
            >
        </div>

        <div>
            <label for="purchased_on">購入日</label><br>
            <input
                type="date"
                id="purchased_on"
                name="purchased_on"
                value="{{ old('purchased_on') }}"
            >
        </div>

        <div>
            <label for="status">状態</label><br>
            <select id="status" name="status">
                <option value="">未選択</option>
                <option value="unread" @selected(old('status') === 'unread')>未読</option>
                <option value="reading" @selected(old('status') === 'reading')>読書中</option>
                <option value="done" @selected(old('status') === 'done')>読了</option>
                <option value="paused" @selected(old('status') === 'paused')>中断</option>
            </select>
        </div>

        <div>
            <label for="memo">メモ</label><br>
            <textarea id="memo" name="memo">{{ old('memo') }}</textarea>
        </div>

        <div>
            <button type="submit">登録する</button>
        </div>
    </form>

    <p>
        <a href="{{ route('books.index') }}">一覧に戻る</a>
    </p>
@endsection