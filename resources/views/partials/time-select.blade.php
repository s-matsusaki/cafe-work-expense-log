@php
    $hourName = $name.'_hour';
    $minuteName = $name.'_minute';
    $defaultHour = filled($value ?? null) ? substr($value, 0, 2) : '';
    $defaultMinute = filled($value ?? null) ? substr($value, 3, 2) : '';
    $selectedHour = old($hourName, $defaultHour);
    $selectedMinute = old($minuteName, $defaultMinute);
@endphp

<div>
    <label for="{{ $hourName }}" class="block text-sm font-semibold text-slate-700">{{ $label }}</label>
    <div class="mt-2 flex items-center gap-2">
        <select
            name="{{ $hourName }}"
            id="{{ $hourName }}"
            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
        >
            <option value="">--</option>
            @for ($hour = 0; $hour < 24; $hour++)
                @php $hourValue = sprintf('%02d', $hour); @endphp
                <option value="{{ $hourValue }}" @selected((string) $selectedHour === $hourValue)>
                    {{ $hourValue }}
                </option>
            @endfor
        </select>
        <span class="text-sm text-slate-600">時</span>

        <select
            name="{{ $minuteName }}"
            id="{{ $minuteName }}"
            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
        >
            <option value="">--</option>
            @foreach (['00', '10', '20', '30', '40', '50'] as $minuteValue)
                <option value="{{ $minuteValue }}" @selected((string) $selectedMinute === $minuteValue)>
                    {{ $minuteValue }}
                </option>
            @endforeach
        </select>
        <span class="text-sm text-slate-600">分</span>
    </div>
</div>
