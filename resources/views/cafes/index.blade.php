@extends('layouts.app')

@section('title', 'カフェ一覧')

@section('content')
    <h2>カフェ一覧</h2>

    <p>
        <a href="{{ route('cafes.create') }}" class="button-link">カフェを登録する</a>
    </p>

    @if ($cafes->isEmpty())
        <p>登録されているカフェはありません。</p>
    @else
        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>カフェ名</th>
                    <th>住所</th>
                    <th>最寄駅</th>
                    <th>メモ</th>
                    <th>登録日時</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cafes as $cafe)
                    <tr>
                        <td>{{ $cafe->id }}</td>
                        <td>{{ $cafe->name }}</td>
                        <td>{{ $cafe->address }}</td>
                        <td>{{ $cafe->nearest_station }}</td>
                        <td>{{ $cafe->memo }}</td>
                        <td>{{ $cafe->created_at }}</td>
                        <td>
                            <a href="{{ route('cafes.show', $cafe) }}">詳細</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection