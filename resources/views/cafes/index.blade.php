<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>カフェ一覧</title>
</head>
<body>
    <h1>カフェ一覧</h1>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <p>
        <a href="{{ route('cafes.create') }}">カフェを登録する</a>
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
</body>
</html>