<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOfferRequest extends FormRequest
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
        $rules = [
            'discount_type'     => 'required|in:fixed,percent',
            'discount_value'    => 'required|numeric|min:0',
            'starts_at'         => 'nullable|date',
            'ends_at'           => 'nullable|date|after_or_equal:starts_at',
            'is_active'         => 'boolean',
            'show_in_slider'    => 'boolean',
            'banner_image'      => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'bg_color'          => 'nullable|string|max:32',
            'link_url'          => 'nullable|string|max:2048',
            'position'          => 'nullable|integer',
        ];

        foreach (active_locals() as $locale) {
            $rules["title.$locale"] = 'required|string|max:255';
            $rules["description.$locale"] = 'nullable|string';
        }

        return $rules;
    }
}
