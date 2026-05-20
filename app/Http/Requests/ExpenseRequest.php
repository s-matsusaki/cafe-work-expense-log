<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpenseRequest extends FormRequest
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
            'expense_date' => ['required', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:0'],
            'expense_type' => ['required', 'string', 'max:50'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'cafe_id' => ['nullable', 'exists:cafes,id'],
            'work_session_id' => ['nullable', 'exists:work_sessions,id'],
            'book_id' => [
                'nullable',
                Rule::exists('books', 'id')->where('user_id', $this->user()->id),
            ],
            'accounting_recorded' => ['nullable', 'boolean'],
            'accounting_recorded_at' => ['nullable', 'date'],
            'accounting_memo' => ['nullable', 'string'],
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
            'title' => '支出内容',
        ];
    }
}
