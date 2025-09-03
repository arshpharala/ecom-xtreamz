<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsRequest extends FormRequest
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
        $id = $this->route('news'); // param binding

        $rules = [
            'category_id'   => 'required|exists:categories,id',
            'slug'          => 'required|string|max:255|unique:news,slug,' . $id,
            'author'        => 'required|string|max:255',
            'position'      => 'nullable|integer',
            'published_at'  => 'required|date',
            'image'         => 'nullable|image|max:2048',
            'thumbnail'     => 'nullable|image|max:2048',
        ];

        foreach (active_locals() as $locale) {
            $rules["title.$locale"] = 'required|string|max:255';
            $rules["intro.$locale"] = 'nullable|string';
            $rules["description.$locale"] = 'nullable|string';
        }

        return $rules;
    }
}
