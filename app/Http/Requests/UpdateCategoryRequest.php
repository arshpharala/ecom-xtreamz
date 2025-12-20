<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
        $id = $this->route('category') ?? $this->route('id');
        return [
            'slug'      => 'required|string|unique:categories,slug,' . $id . ',id',
            'name'      => 'required|array',
            'name.*'    => 'required|string|max:255',

            'icon'      => 'nullable|image|max:2048',
            'image' => 'nullable|image|max:2048',
            'banner_image' => 'nullable|image|max:2048',

            'parent_id' => 'nullable|uuid|exists:categories,id',

            'attributes' => 'nullable|array',
            'attributes.*' => 'uuid|exists:attributes,id',

            'position'  => 'nullable|integer',
            'is_visible' => 'boolean',
            'show_in_menu' => 'boolean',
            'show_on_homepage' => 'boolean',

            'text_color' => 'nullable|string|max:7',
            'background_color' => 'nullable|string|max:7',
        ];
    }
}
