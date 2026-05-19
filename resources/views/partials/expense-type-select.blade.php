<div>
    <label for="{{ $id ?? 'expense_type' }}">
        {{ $label ?? '支出種別' }}
    </label>

    @if (($showBreak ?? false) === true)
        <br>
    @endif


    <select name="{{ $name ?? 'expense_type' }}" id="{{ $id ?? 'expense_type' }}">
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