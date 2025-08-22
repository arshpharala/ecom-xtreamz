<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventorySourceRequest extends FormRequest
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
            'code'  => ['required', 'string', 'max:40', 'unique:inventory_sources,code'],
            'name'  => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],

            'contact_name'  => ['nullable', 'string', 'max:120'],
            'contact_email' => ['nullable', 'email', 'max:160'],
            'contact_phone' => ['nullable', 'string', 'max:60'],
            'contact_fax'   => ['nullable', 'string', 'max:60'],

            'country_id'    => ['required', 'integer', 'exists:countries,id'],
            'province_id'   => ['nullable', 'integer', 'exists:provinces,id'],
            'city_id'       => ['nullable', 'integer', 'exists:cities,id'],
            'street'       => ['nullable', 'string', 'max:160'],
            'postcode'    => ['nullable', 'string', 'max:40'],

            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'priority' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}
