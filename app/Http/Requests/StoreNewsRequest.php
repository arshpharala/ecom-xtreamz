<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsRequest extends FormRequest
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
        $locales = active_locals();
        $rules = [
            'slug'          => ['required', 'string', 'max:255', 'unique:news,slug'],
            'position'      => ['nullable', 'integer'],
            'is_guide'      => ['nullable'],
            'is_active'     => ['nullable'],
            'is_featured'   => ['nullable'],
            'category_id'   => ['required'],
            'image'         => ['required', 'image', 'max:4096'],
            'color'         => ['nullable', 'string', 'max:7'],
            'author' => ['required', 'string'],
        ];
        foreach ($locales as $locale) {
            $rules["title.$locale"] = ['required', 'string', "max:255"];
            $rules["intro.$locale"] = ['nullable', 'string'];
            $rules["description.$locale"] = ['nullable', 'string'];
        }
        return $rules;
    }
}
