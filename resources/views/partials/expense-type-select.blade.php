<div>
    <label for="{{ $id ?? 'expense_type' }}" class="block text-sm font-semibold text-slate-700">
        {{ $label ?? '支出種別' }}
    </label>

    <select
        name="{{ $name ?? 'expense_type' }}"
        id="{{ $id ?? 'expense_type' }}"
        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
    >
        @if (($showAllOption ?? false) === true)
            <option value="">すべて</option>
        @else
            <option value="">選択してください</option>
        @endif

        <option value="cafe" @selected(($selected ?? '') === 'cafe')>カフェ代</option>
        <option value="book" @selected(($selected ?? '') === 'book')>書籍代</option>
        <option value="saas" @selected(($selected ?? '') === 'saas')>SaaS代</option>
        <option value="transport" @selected(($selected ?? '') === 'transport')>交通費</option>
        <option value="other" @selected(($selected ?? '') === 'other')>その他</option>
    </select>
</div>
