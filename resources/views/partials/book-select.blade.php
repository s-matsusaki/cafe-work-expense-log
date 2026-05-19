<div>
    <label for="book_id">関連書籍</label><br>
    <select id="book_id" name="book_id">
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