<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCountryRequest extends FormRequest
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
            'code'              => 'required|string|size:2|unique:countries,code',
            'name'              => 'required|string|unique:countries,name',
            'currency_id'       => 'required|exists:currencies,id',
            'tax_percentage'    => 'required',
            'tax_label'         => 'required|in:VAT,TAX,GST',
            'icon'              => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
        ];
    }
}
