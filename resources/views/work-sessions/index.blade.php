@extends('layouts.app')

@section('title', '作業記録一覧')

@section('content')
    <h2>作業記録一覧</h2>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <p>
        <a href="{{ route('work-sessions.create') }}">作業記録を登録する</a>
    </p>

    <p>
        <a href="{{ route('cafes.index') }}">カフェ一覧へ</a>
    </p>

    @if ($workSessions->isEmpty())
        <p>登録されている作業記録はありません。</p>
    @else
        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>作業日</th>
                    <th>タイトル</th>
                    <th>カフェ</th>
                    <th>作業時間</th>
                    <th>カテゴリ</th>
                    <th>メモ</th>
                    <th>登録日時</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($workSessions as $workSession)
                    <tr>
                        <td>{{ $workSession->id }}</td>
                        <td>{{ $workSession->work_date?->format('Y-m-d') }}</td>
                        <td>{{ $workSession->title }}</td>
                        <td>{{ $workSession->cafe?->name ?? '未設定'}}</td>
                        <td>
                            @if (!is_null($workSession->work_minutes))
                                {{ $workSession->work_minutes }}分
                            @else
                                未入力
                            @endif
                        </td>
                        <td>{{ $workSession->category ?? '未入力' }}</td>
                        <td>{{ $workSession->memo }}</td>
                        <td>{{ $workSession->created_at }}</td>
                        <td>
                            <a href="{{ route('work-sessions.show', $workSession) }}">詳細</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection