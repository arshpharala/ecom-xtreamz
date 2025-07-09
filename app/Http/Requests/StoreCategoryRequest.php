<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'slug'      => 'required|string|unique:categories,slug',
            'name'      => 'required|array',
            'name.*'    => 'required|string|max:255',
            'icon'      => 'nullable|string|max:255',
            'parent_id' => 'nullable|uuid|exists:categories,id',
            'attributes' => 'nullable|array',
            'attributes.*' => 'uuid|exists:attributes,id',
            'position'  => 'nullable|integer',
            'is_visible' => 'boolean'
        ];
    }
}
