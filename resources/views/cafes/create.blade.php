@extends('layouts.app')

@section('title', 'カフェ登録')

@section('content')
    <h2>カフェ登録</h2>

    <form action="{{ route('cafes.store') }}" method="POST">
        @csrf

        <div>
            <label for="name">カフェ名</label><br>
            <input type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
            >
        </div>

        <div>
            <label for="address">住所</label><br>
            <input type="text"
                id="address"
                name="address"
                value="{{ old('address') }}"
            >
        </div>

        <div>
            <label for="nearest_station">最寄駅</label><br>
            <input type="text"
                id="nearest_station"
                name="nearest_station"
                value="{{ old('nearest_station') }}"
            >
        </div>

        <div>
            <label for="memo">メモ</label>
            <textarea name="memo" id="memo" cols="30" rows="10">{{ old('memo') }}</textarea>
        </div>

        <div>
            <button type="submit">登録する</button>
        </div>
    </form>

    <p>
        <a href="{{ route('cafes.index') }}" class="button-link">一覧に戻る</a>
    </p>
@endsection