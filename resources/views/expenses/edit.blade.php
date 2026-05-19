@extends('layouts.app')

@section('title', '支出編集')

@section('content')
    <h2>支出編集</h2>

    <form action="{{ route('expenses.update', $expense) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="expense_date">支出日</label><br>
            <input
                type="date"
                id="expense_date"
                name="expense_date"
                value="{{ old('expense_date', $expense->expense_date?->format('Y-m-d')) }}"
            >
        </div>

        <div>
            <label for="title">支出内容</label><br>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title', $expense->title) }}"
            >
        </div>

        <div>
            <label for="amount">金額</label><br>
            <input
                type="number"
                id="amount"
                name="amount"
                min="0"
                value="{{ old('amount', $expense->amount) }}"
            >
        </div>

        @include('partials.expense-type-select', [
            'name' => 'expense_type',
            'id' => 'expense_type',
            'label' => '支出種別',
            'selected' => old('expense_type', $expense->expense_type),
            'showAllOption' => false,
            'showBreak' => true,
        ])

        <div>
            <label for="payment_method">支払方法</label><br>
            <select id="payment_method" name="payment_method">
                <option value="">未選択</option>
                <option value="cash" @selected(old('payment_method', $expense->payment_method) === 'cash')>現金</option>
                <option value="card" @selected(old('payment_method', $expense->payment_method) === 'card')>カード</option>
                <option value="qr" @selected(old('payment_method', $expense->payment_method) === 'qr')>QR決済</option>
                <option value="other" @selected(old('payment_method', $expense->payment_method) === 'other')>その他</option>
            </select>
        </div>

        @include('partials.cafe-select', [
            'label' => '関連カフェ',
            'cafes' => $cafes,
            'name' => 'cafe_id',
            'id' => 'cafe_id',
            'emptyLabel' => '未選択',
            'selectedCafeId' => old('cafe_id'),
            'showBreak' => true,
        ])

        @include('partials.book-select', [
            'books' => $books,
            'selectedBookId' => $expense->book_id,
        ])

        <div>
            <label for="work_session_id">関連作業記録</label><br>
            <select name="work_session_id" id="work_session_id">
                <option value="">未選択</option>
                @foreach ($workSessions as $workSession)
                    <option
                        value="{{ $workSession->id }}"
                        @selected((string) old('work_session_id', $workSession->id) === (string) $workSession->id)
                    >
                        {{ $workSession->work_date?->format('Y-m-d') }}
                        -
                        {{ $workSession->title }}
                        @if ($workSession->cafe)
                            （{{ $workSession->cafe->name }}）
                        @endif
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>
                <input
                    type="checkbox"
                    name="accounting_recorded"
                    value="1"
                    @checked(old('accounting_recorded', $expense->accounting_recorded))
                >
                会計ソフトに記録済み
            </label>
        </div>
        
        <div>
            <label for="accounting_recorded_at">会計ソフト記録日時</label><br>
            <input
                type="datetime-local"
                id="accounting_recorded_at"
                name="accounting_recorded_at"
                value="{{ old('accounting_recorded_at', $expense->accounting_recorded_at?->format('Y-m-d\TH:i')) }}"
            >
        </div>

        <div>
            <label for="memo">メモ</label><br>
            <textarea name="memo" id="memo" cols="30" rows="10">{{ old('memo', $expense->memo) }}</textarea>
        </div>

        <div>
            <button type="submit">更新する</button>
        </div>
    </form>

    <p>
        <a href="{{ route('expenses.show', $expense) }}">詳細に戻る</a>
    </p>

    <p>
        <a href="{{ route('expenses.index') }}" class="button-link">一覧に戻る</a>
    </p>
@endsection