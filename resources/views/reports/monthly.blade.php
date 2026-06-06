@extends('layouts.app')

@section('title', '月次分析')

@section('content')
    <div class="mb-6">
        <p class="text-sm font-medium text-blue-600">Monthly Report</p>
        <h2 class="mt-1 text-2xl font-bold text-slate-900">月次分析レポート</h2>

        <p class="mt-2 text-sm text-slate-500">
            作業時間・支出・会計ソフト未記録件数を月ごとに確認できます。
        </p>
    </div>

    <div id="monthly-report-app"></div>

    @viteReactRefresh
    @vite(['resources/js/monthly-report.jsx'])
@endsection
