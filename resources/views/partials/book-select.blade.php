<div>
    <label for="book_id" class="block text-sm font-semibold text-slate-700">関連書籍</label>
    <select
        id="book_id"
        name="book_id"
        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
    >
        <option value="">未選択</option>

        @foreach ($books as $book)
            <option
                value="{{ $book->id }}"
                @selected((string) old('book_id', $selectedBookId ?? null) === (string) $book->id)
            >
                {{ $book->title }}
                @if ($book->purchased_on)
                    （{{ $book->purchased_on->format('Y-m-d') }}）
                @endif
            </option>
        @endforeach
    </select>
</div>
