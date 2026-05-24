@extends('layouts.app')

@section('title', '場所登録')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-blue-600">New Place</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">場所登録</h2>
            <p class="mt-2 text-sm text-slate-500">作業場所を登録します。</p>
        </div>

        <a
            href="{{ route('cafes.index') }}"
            class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
        >
            一覧に戻る
        </a>
    </div>

    <form action="{{ route('cafes.store') }}" method="POST" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        @csrf

        <div class="grid gap-6">
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700">場所名</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    placeholder="例: スターバックス コーヒー 天神地下街店、自宅、ラウンジ"
                >
            </div>

            <div>
                <label for="address" class="block text-sm font-semibold text-slate-700">住所</label>
                <input
                    type="text"
                    id="address"
                    name="address"
                    value="{{ old('address') }}"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    placeholder="例: 福岡県福岡市中央区天神2丁目地下1号"
                >
            </div>

            <div>
                <label for="nearest_station" class="block text-sm font-semibold text-slate-700">最寄駅</label>
                <input
                    type="text"
                    id="nearest_station"
                    name="nearest_station"
                    value="{{ old('nearest_station') }}"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    placeholder="例: 天神駅"
                >
            </div>

            <div>
                <label for="memo" class="block text-sm font-semibold text-slate-700">メモ</label>
                <textarea
                    name="memo"
                    id="memo"
                    rows="6"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    placeholder="例: 天神地下街内。Wi-Fiあり、午前中は比較的作業しやすい。"
                >{{ old('memo') }}</textarea>
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-md bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
                >
                    登録する
                </button>
            </div>
        </div>
    </form>
@endsection
