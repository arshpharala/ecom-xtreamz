<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTestimonialRequest extends FormRequest
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
            'rating'            => 'required|integer|min:1|max:5',
            'is_active'         => 'boolean',
            'position'          => 'nullable|integer',
            'image'             => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'company_logo'      => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'designation'       => 'nullable|string',
            'company_name'      => 'nullable|string',

        ];

        foreach (active_locals() as $locale) {
            $rules["name.$locale"] = 'required|string|max:255';
            $rules["description.$locale"] = 'nullable|string';
        }

        return $rules;
    }
}
