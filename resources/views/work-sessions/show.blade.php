@extends('layouts.app')

@section('title', '作業記録詳細')

@section('content')
    <h2>作業記録詳細</h2>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <dl>
        <dt>ID</dt>
        <dd>{{ $workSession->id }}</dd>

        <dt>作業日</dt>
        <dd>{{ $workSession->work_date?->format('Y-m-d') }}</dd>

        <dt>タイトル</dt>
        <dd>{{ $workSession->title }}</dd>

        <dt>カフェ</dt>
        <dd>{{ $workSession->cafe?->name ?? '未設定' }}</dd>

        <dt>作業時間</dt>
        <dd>
            @if (!is_null($workSession->work_minutes))
                {{ $workSession->work_minutes }}分
            @else
                未入力
            @endif
        </dd>

        <dt>カテゴリ</dt>
        <dd>{{ $workSession->category ?? '未入力' }}</dd>

        <dt>メモ</dt>
        <dd>{!! nl2br(e($workSession->memo ?? '')) !!}</dd>

        <dt>登録日時</dt>
        <dd>{{ $workSession->created_at }}</dd>

        <dt>更新日時</dt>
        <dd>{{ $workSession->updated_at}}</dd>

        <p>
            <a href="{{ route('work-sessions.edit', $workSession) }}">編集する</a>
        </p>

        <form action="{{ route('work-sessions.destroy', $workSession) }}" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit" onclick="return confirm('本当に削除しますか？')">
                削除する
            </button>
        </form>

        <p>
            <a href="{{ route('work-sessions.index') }}">一覧に戻る</a>
        </p>
    </dl>
@endsection