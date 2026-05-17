@extends('layouts.app')

@section('title', 'カフェ詳細')

@section('content')
    <h2>カフェ詳細</h2>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <dl>
        <dt>ID</dt>
        <dd> {{ $cafe->id }}</dd>

        <dt>カフェ名</dt>
        <dd>{{ $cafe->name }}</dd>

        <dt>住所</dt>
        <dd>{{ $cafe->address ?? '未入力' }}</dd>

        <dt>最寄駅</dt>
        <dd>{{ $cafe->nearest_station ?? '未入力' }}</dd>

        <dt>メモ</dt>
        <dd>{!! nl2br(e($cafe->memo ?? '')) !!}</dd>

        <dt>登録日時</dt>
        <dd>{{ $cafe->created_at }}</dd>

        <dt>更新日時</dt>
        <dd>{{ $cafe->updated_at }}</dd>
    </dl>

    <p>
        <a href="{{ route('cafes.edit', $cafe) }}">編集する</a>
    </p>

    <form action="{{ route('cafes.destroy', $cafe) }}" method="POST">
        @csrf
        @method('DELETE')

        <button type="submit" onclick="return confirm('本当に削除しますか？')">
            削除する
        </button>
    </form>

    <p>
        <a href="{{ route('cafes.index') }}">一覧に戻る</a>
    </p>
@endsection