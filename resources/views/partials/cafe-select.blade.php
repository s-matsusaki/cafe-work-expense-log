<div>
    <label for="{{ $id ?? 'cafe_id' }}" class="block text-sm font-semibold text-slate-700">{{ $label ?? 'カフェ' }}</label>

    <select
        id="{{ $id ?? 'cafe_id' }}"
        name="{{ $name ?? 'cafe_id' }}"
        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
    >
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
