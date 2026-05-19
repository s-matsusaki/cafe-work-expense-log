@extends('layouts.app')

@section('title', 'カフェ編集')

@section('content')
    <h2>カフェ編集</h2>

    <form action="{{ route('cafes.update', $cafe) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="name">カフェ名</label><br>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $cafe->name) }}"
            >
        </div>

        <div>
            <label for="address">住所</label><br>
            <input
                type="text"
                id="address"
                name="address"
                value="{{ old('address', $cafe->address) }}"
            >
        </div>

        <div>
            <label for="nearest_station">最寄駅</label><br>
            <input
                type="text"
                id="nearest_station"
                name="nearest_station"
                value="{{ old('nearest_station', $cafe->nearest_station) }}"
            >
        </div>

        <div>
            <label for="memo">メモ</label><br>
            <textarea name="memo" id="memo" cols="30" rows="10">{{ old('memo', $cafe->memo)}}</textarea>
        </div>

        <div>
            <button type="submit">更新する</button>
        </div>

    </form>

    <p>
        <a href="{{ route('cafes.show', $cafe) }}">詳細に戻る</a>
    </p>

    <p>
        <a href="{{ route('cafes.index') }}" class="button-link">一覧に戻る</a>
    </p>
@endsection