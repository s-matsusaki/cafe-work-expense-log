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

    <form action="{{ route('work-sessions.index') }}" method="GET">
        <div>
            <label for="work_month">作業月</label>
            <input
                type="month"
                id="work_month"
                name="work_month"
                value="{{ request('work_month') }}"
            >
            <small>例：2026-05</small>
        </div>

        @include('partials.cafe-select', [
            'label' => 'カフェ',
            'cafes' => $cafes,
            'name' => 'cafe_id',
            'id' => 'cafe_id',
            'emptyLabel' => 'すべて',
            'showBreak' => false,
        ])

        <div>
            <label for="category">カテゴリ</label>
            <select name="category" id="category">
                <option value="">すべて</option>
                @foreach ($categories as $category)
                    <option
                        value="{{ $category }}"
                        @selected(request('category') === $category)
                    >
                        {{ $category }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <button type="submit">絞り込む</button>
            <a href="{{ route('work-sessions.index') }}">条件をクリア</a>
        </div>
    </form>

    <section>
        <h3>集計</h3>

        <p>
            表示中の作業時間合計：
            <strong>{{ number_format($totalWorkMinutes) }}分</strong>
        </p>

        <p>
            時間換算：
            <strong>{{ intdiv($totalWorkMinutes, 60) }}時間{{ $totalWorkMinutes % 60 }}分</strong>
        </p>

        <p>
            表示件数：
            {{ $workSessions->count() }}件
        </p>
    </section>

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