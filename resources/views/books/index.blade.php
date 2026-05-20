@extends('layouts.app')

@section('title', '書籍一覧')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-blue-600">Books</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">書籍一覧</h2>
            <p class="mt-2 text-sm text-slate-500">
                作業や学習に使う書籍を管理します。
            </p>
        </div>

        <a
            href="{{ route('books.create') }}"
            class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
        >
            書籍を登録する
        </a>
    </div>

    @if ($books->isEmpty())
        <div class="rounded-lg border border-dashed border-slate-300 bg-white px-6 py-12 text-center shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">登録されている書籍はありません</h3>
            <p class="mt-2 text-sm text-slate-500">最初の書籍を登録して、購入日や読書状況を記録しましょう。</p>
            <a
                href="{{ route('books.create') }}"
                class="mt-5 inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
            >
                書籍を登録する
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
                                タイトル
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                購入日
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                状態
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">
                                操作
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($books as $book)
                            <tr class="hover:bg-slate-50">
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">
                                    #{{ $book->id }}
                                </td>
                                <td class="px-4 py-4 text-sm font-semibold text-slate-900">
                                    {{ $book->title }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">
                                    {{ $book->purchased_on?->format('Y-m-d') ?? '未入力' }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">
                                    {{ $book->status ?? '未入力' }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-right text-sm">
                                    <a
                                        href="{{ route('books.show', $book) }}"
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
