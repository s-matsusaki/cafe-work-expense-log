<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class WorkSessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cafe_id' => ['nullable', 'exists:cafes,id'],
            'work_date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'title' => ['required', 'string', 'max:255'],
            'work_minutes' => ['nullable', 'integer', 'min:0', 'multiple_of:10'],
            'category' => ['nullable', 'string', 'max:50'],
            'memo' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->mergeTimeParts('start_time');
        $this->mergeTimeParts('end_time');
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => '作業タイトル',
            'start_time' => '開始時刻',
            'end_time' => '終了時刻',
            'work_minutes' => '作業時間（分）',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            foreach (['start_time' => '開始時刻', 'end_time' => '終了時刻'] as $field => $label) {
                $hour = $this->input($field.'_hour');
                $minute = $this->input($field.'_minute');

                if ((blank($hour) && filled($minute)) || (filled($hour) && blank($minute))) {
                    $validator->errors()->add($field, $label.'は時と分を両方指定してください。');
                }
            }

            foreach (['start_time' => '開始時刻', 'end_time' => '終了時刻'] as $field => $label) {
                $time = $this->input($field);

                if (blank($time)) {
                    continue;
                }

                $minute = (int) substr($time, 3, 2);

                if ($minute % 10 !== 0) {
                    $validator->errors()->add($field, $label.'は10分単位で指定してください。');
                }
            }

            if (blank($this->start_time) || blank($this->end_time)) {
                return;
            }

            if ($this->end_time <= $this->start_time) {
                $validator->errors()->add('end_time', '終了時刻は開始時刻より後の時刻を指定してください。');
            }
        });
    }

    private function mergeTimeParts(string $field): void
    {
        $hourField = $field.'_hour';
        $minuteField = $field.'_minute';

        if (! $this->has($hourField) && ! $this->has($minuteField)) {
            return;
        }

        $hour = $this->input($hourField);
        $minute = $this->input($minuteField);

        if (blank($hour) || blank($minute)) {
            $this->merge([$field => null]);

            return;
        }

        $this->merge([
            $field => sprintf('%02d:%02d', (int) $hour, (int) $minute),
        ]);
    }
}
