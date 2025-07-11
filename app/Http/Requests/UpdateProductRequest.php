<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        $productId = $this->route('product') ?? $this->route('id') ?? $this->product;

        $nameRules = [];
        $descRules = [];
        foreach ($locales as $locale) {
            $nameRules["name.$locale"] = 'required|string|max:255';
            $descRules["description.$locale"] = 'nullable|string';
        }

        return array_merge([
            'slug' => 'required|string|max:255|unique:products,slug,' . $productId,
            // 'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'position' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'show_in_slider' => 'nullable|boolean'
        ], $nameRules, $descRules);
    }
}
