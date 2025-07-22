<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCurrencyRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|size:3|unique:currencies,code',
            'name' => 'required|string|unique:currencies,name',
            'symbol' => 'required|string|max:10',
            'decimal' => 'nullable|integer|min:0|max:6',
            'group_separator' => 'nullable|string|max:10',
            'decimal_separator' => 'nullable|string|max:10',
            'currency_position' => 'required|in:Left,Right',
        ];
    }
}
