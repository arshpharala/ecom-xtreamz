<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBannerRequest extends FormRequest
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
            'image'       => 'required|image|max:2048',
            'background'  => 'required|image|max:4096',
            'text_color'  => 'required|string|max:10',
            'btn_text'    => 'nullable|string|max:100',
            'btn_color'   => 'nullable|string|max:10',
            'btn_link'    => 'nullable|string|max:255',
            'position'    => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',

            // translations
            'title.*'       => 'nullable|string|max:255',
            'subtitle.*'    => 'nullable|string|max:255',
            'description.*' => 'nullable|string',
        ];
    }
}
