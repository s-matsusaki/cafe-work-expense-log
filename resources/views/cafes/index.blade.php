@extends('layouts.app')

@section('title', '場所一覧')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-blue-600">Places</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">場所一覧</h2>
            <p class="mt-2 text-sm text-slate-500">
                カフェや自宅、ラウンジなど、作業に利用する場所を管理します。
            </p>
        </div>

        <a
            href="{{ route('cafes.create') }}"
            class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
        >
            場所を登録する
        </a>
    </div>

    @if ($cafes->isEmpty())
        <div class="rounded-lg border border-dashed border-slate-300 bg-white px-6 py-12 text-center shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">登録されている場所はありません</h3>
            <p class="mt-2 text-sm text-slate-500">作業場所を登録しましょう。</p>
            <a
                href="{{ route('cafes.create') }}"
                class="mt-5 inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
            >
                場所を登録する
            </a>
        </div>
    @else
        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                ID
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                場所名
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">
                                操作
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($cafes as $cafe)
                            <tr class="hover:bg-slate-50">
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">
                                    #{{ $cafe->id }}
                                </td>
                                <td class="px-4 py-4 text-sm font-semibold text-slate-900">
                                    {{ $cafe->name }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-right text-sm">
                                    <a
                                        href="{{ route('cafes.show', $cafe) }}"
                                        class="inline-flex items-center justify-center rounded-md border border-slate-300 px-3 py-1.5 font-medium text-slate-700 hover:bg-slate-50"
                                    >
                                        詳細
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
