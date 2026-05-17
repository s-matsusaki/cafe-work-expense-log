@extends('layouts.app')

@section('title', '支出登録')

@section('content')
    <h2>支出登録</h2>

    <form action="{{ route('expenses.store') }}" method="POST">
        @csrf

        <div>
            <label for="expense_date">支出日</label><br>
            <input
                type="date"
                id="expense_date"
                name="expense_date"
                value="{{ old('expense_date', now()->toDateString()) }}"
            >
        </div>

        <div>
            <label for="title">支出内容</label><br>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title') }}"
                placeholder="例：コーヒー代"
            >
        </div>

        <div>
            <label for="amount">金額</label><br>
            <input
                type="number"
                id="amount"
                name="amount"
                min="0"
                value="{{ old('amount') }}"
            >
        </div>

        <div>
            <label for="expense_type">支出種別</label><br>
            <select name="expense_type" id="expense_type">
                <option value="">選択してください</option>
                <option value="cafe" @selected(old('expense_type') === 'cafe')>カフェ代</option>
                <option value="book" @selected(old('expense_type') === 'book')>書籍代</option>
                <option value="saas" @selected(old('expense_type') === 'saas')>SaaS代</option>
                <option value="transport" @selected(old('expense_type') === 'transport')>交通費</option>
                <option value="other" @selected(old('expense_type') === 'other')>その他</option>
            </select>
        </div>

        <div>
            <label for="payment_method">支払方法</label><br>
            <select name="payment_method" id="payment_method">
                <option value="">未選択</option>
                <option value="cash" @selected(old('payment_method') === 'cash')>現金</option>
                <option value="card" @selected(old('payment_method') === 'card')>カード</option>
                <option value="qr" @selected(old('payment_method') === 'qr')>QR</option>
                <option value="other" @selected(old('payment_method') === 'other')>その他</option>
            </select>
        </div>

        <div>
            <label for="cafe_id">関連カフェ</label><br>
            <select name="cafe_id" id="cafe_id">
                <option value="">未選択</option>
                @foreach ($cafes as $cafe)
                    <option
                        value="{{ $cafe->id }}"
                        @selected((string) old('cafe_id') === (string) $cafe->id)
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
            <label for="work_session_id">関連作業記録</label><br>
            <select name="work_session_id" id="work_session_id">
                <option value="">未選択</option>
                @foreach ($workSessions as $workSession)
                    <option
                        value="{{ $workSession->id }}"
                        @selected((string) old('work_session_id') === (string) $workSession->id)
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
                    @checked(old('accounting_recorded'))
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
                value="{{ old('accounting_recorded_at') }}"
            >
        </div>

        <div>
            <label for="accounting_memo">会計メモ</label><br>
            <textarea name="accounting_memo" id="accounting_memo" cols="30" rows="10">{{ old('accounting_memo') }}</textarea>
        </div>
        
        <div>
            <label for="memo">メモ</label><br>
            <textarea name="memo" id="memo" cols="30" rows="10">{{ old('memo') }}</textarea>
        </div>

        <div>
            <button type="submit">登録する</button>
        </div>
    </form>
    
    <p>
        <a href="{{ route('expenses.index') }}">一覧に戻る</a>
    </p>
@endsection