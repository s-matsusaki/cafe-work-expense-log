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
            'title' => ['required', 'string', 'max:255'],
            'work_minutes' => ['nullable', 'integer', 'min:0'],
            'category' => ['nullable', 'string', 'max:50'],
            'memo' => ['nullable', 'string'],
        ];
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
            'work_minutes' => '作業時間（分）',
        ];
    }
}
