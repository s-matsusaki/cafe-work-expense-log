@extends('layouts.app')

@section('title', '作業記録編集')

@section('content')
    <h2>作業記録編集</h2>

    <form action="{{ route('work-sessions.update', $workSession) }}" method="POST">
        @csrf
        @method("PUT")

        <div>
            <label for="work_date">作業日</label><br>
            <input
                type="date"
                id="work_date"
                name="work_date"
                value="{{ old('work_date', $workSession->work_date?->format('Y-m-d')) }}"    
            >
        </div>

        <div>
            <label for="title">作業タイトル</label><br>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title', $workSession->title) }}"
            >
        </div>

        <div>
            <label for="cafe_id">カフェ</label><br>
            <select name="cafe_id" id="cafe_id">
                <option value="">未選択</option>
                @foreach ($cafes as $cafe)
                    <option
                        value="{{ $cafe->id }}"
                        @selected((string) old('cafe_id', $workSession->cafe_id) === (string) $cafe->id)
                    >
                        {{ $cafe->name }}
                        @if ($cafe->nearest_station)
                            （{{ $cafe->nearest_station }}）
                        @endif
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="work_minutes">作業時間（分）</label>
            <input
                type="number"
                id="work_minutes"
                name="work_minutes"
                min="0"
                value="{{ old('work_minutes', $workSession->work_minutes) }}"
            >
        </div>

        <div>
            <label for="category">カテゴリ</label><br>
            <input
                type="text"
                id="category"
                name="category"
                value="{{ old('category', $workSession->category) }}"
                placeholder="例:開発、勉強、読書"
            >
        </div>

        <div>
            <label for="memo">メモ</label>
            <textarea name="memo" id="memo" cols="30" rows="10">{{ old('memo', $workSession->memo) }}</textarea>
        </div>

        <div>
            <button type="submit">更新する</button>
        </div>

        <p>
            <a href="{{ route('work-sessions.show', $workSession) }}">詳細に戻る</a>
        </p>

        <p>
            <a href="{{ route('work-sessions.index') }}">一覧に戻る</a>
        </p>
    </form>
@endsection