@extends('layouts.app')

@section('title', 'ダッシュボード')

@section('content')
    <h2>ダッシュボード</h2>

    <p>{{ $currentMonth }} の状況</p>

    <section>
        <h3>今月の集計</h3>

        <ul>
            <li>
                今月の作業時間：
                <strong>
                    {{ number_format($monthlyWorkMinutes) }}分
                    （{{ intdiv($monthlyWorkMinutes, 60) }}時間{{ $monthlyWorkMinutes %60 }}分）
                </strong>
            </li>

            <li>
                今月の支出合計：
                <strong>{{ number_format($monthlyExpenseAmount) }}円</strong>
            </li>

            <li>
                会計ソフト未記録の支出：
                <strong>{{ $unrecordedExpenseCount }}件</strong>
            </li>

            <li>
                読書中の書籍：
                <strong>{{ $readingBookCount }}冊</strong>
            </li>

            <li>
                未読の書籍：
                <strong>{{ $unreadBookCount }}冊</strong>
            </li>
        </ul>
    </section>

    <hr>

    <section>
        <h3>メニュー</h3>

        <ul>
            <li>
                <a href="{{ route('cafes.index') }}">カフェ一覧</a>
            </li>
            <li>
                <a href="{{ route('work-sessions.index') }}">作業記録一覧</a>
            </li>
            <li>
                <a href="{{ route('books.index') }}">書籍一覧</a>
            </li>
            <li>
                <a href="{{ route('expenses.index') }}">支出一覧</a>
            </li>
        </ul>
    </section>

    <hr>

    <section>
        <h3>直近の作業記録</h3>

        @if ($recentWorkSessions->isEmpty())
            <p>作業記録はまだありません</p>
        @else
            <table border="1" cellpadding="8">
                <thead>
                    <tr>
                        <th>作業日</th>
                        <th>タイトル</th>
                        <th>カフェ</th>
                        <th>作業時間</th>
                        <th>詳細</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentWorkSessions as $workSession)
                        <tr>
                            <td>{{ $workSession->work_date?->format('Y-m-d') }}</td>
                            <td>{{ $workSession->title }}</td>
                            <td>{{ $workSession->cafe?->name ?? '未設定' }}</td>
                            <td>
                                @if (!is_null($workSession->work_minutes))
                                    {{ $workSession->work_minutes }}分
                                @else
                                    未入力
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('work-sessions.show', $workSession) }}">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>

    <hr>

    <section>
        <h3>直近の支出</h3>

        @if ($recentExpenses->isEmpty())
            <p>支出記録はまだありません。</p>
        @else
            <table border="1" cellpadding="8">
                <thead>
                    <tr>
                        <th>支出日</th>
                        <th>内容</th>
                        <th>金額</th>
                        <th>種別</th>
                        <th>会計記録</th>
                        <th>詳細</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentExpenses as $expense)
                        <tr>
                            <td>{{ $expense->expense_date?->format('Y-m-d') }}</td>
                            <td>{{ $expense->title }}</td>
                            <td>{{ number_format($expense->amount) }}円</td>
                            <td>{{ $expense->expense_type }}</td>
                            <td>{{ $expense->accounting_recorded ? '済' : '未' }}</td>
                            <td>
                                <a href="{{ route('expenses.show', $expense) }}">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>

    <hr>

    <section>
        <h3>直近の書籍</h3>

        @if ($recentBooks->isEmpty())
            <p>書籍記録はまだありません。</p>
        @else
            <table border="1" cellpadding="8">
                <thead>
                    <tr>
                        <th>タイトル</th>
                        <th>購入日</th>
                        <th>状態</th>
                        <th>詳細</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentBooks as $book)
                        <tr>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->purchased_on?->format('Y-m-d') ?? '未入力' }}</td>
                            <td>{{ $book->status ?? '未入力' }}</td>
                            <td>
                                <a href="{{ route('books.show', $book) }}">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>
@endsection