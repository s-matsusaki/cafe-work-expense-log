<div>
    <label for="{{ $id ?? 'cafe_id' }}">{{ $label ?? 'カフェ' }}</label>

    @if (($showBreak ?? false) === true)
        <br>
    @endif

    <select id="{{ $id ?? 'cafe_id' }}" name="{{ $name ?? 'cafe_id' }}">
        <option value="">{{ $emptyLabel ?? '未選択' }}</option>

        @foreach ($cafes as $cafe)
            <option
                value="{{ $cafe->id }}"
                @selected((string) ($selectedCafeId ?? request($name ?? 'cafe_id')) === (string) $cafe->id)
            >
                {{ $cafe->name }}
                @if ($cafe->nearest_station)
                    （{{ $cafe->nearest_station }}）
                @endif
            </option>
        @endforeach
    </select>
</div>