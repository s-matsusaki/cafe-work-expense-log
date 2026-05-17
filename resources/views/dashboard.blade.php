@extends('layouts.app')

@section('title', 'ダッシュボード')

@section('content')
    <h2>ダッシュボード</h2>

    <p>カフェ作業記録システムのメニューです。</p>

    <ul>
        <li>
            <a href="{{ route('cafes.index') }}">カフェ一覧</a>
        </li>
        <li>
            <a href="{{ route('work-sessions.index') }}">作業記録一覧</a>
        </li>
        <li>
            <a href="{{ route('expenses.index') }}">支出一覧</a>
        </li>
    </ul>

    <hr>

    <h3>次にやること</h3>

    <ul>
        <li>作業記録を登録する</li>
        <li>支出を登録する</li>
        <li>会計ソフト記録済みチェックを確認する</li>
    </ul>
@endsection