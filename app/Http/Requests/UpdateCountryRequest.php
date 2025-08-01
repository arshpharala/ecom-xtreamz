<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCountryRequest extends FormRequest
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
        $id = $this->route('country');

        return [
            'code'              => 'required|string|size:2|unique:countries,code,' . $id,
            'name'              => 'required|string|unique:countries,name,' . $id,
            'currency_id'       => 'required|exists:currencies,id',
            'tax_percentage'    => 'required',
            'tax_label'         => 'required|in:VAT,TAX,GST',
            'icon'              => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
        ];
    }
}
