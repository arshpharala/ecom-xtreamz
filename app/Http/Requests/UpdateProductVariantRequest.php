<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductVariantRequest extends FormRequest
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
        $variantId = $this->route('variant') ?? $this->route('id') ?? $this->variant;

        return [
            'sku'           => 'required|string|max:255|unique:product_variants,sku,' . $variantId,
            'price'         => 'required|numeric',
            'stock'         => 'required|integer',
            'attributes'    => 'required|array',
            'attributes.*'  => 'required|exists:attribute_values,id',
            'tags'          => 'required|array',
            'tags.*'        => 'required|exists:tags,id',
        ];
    }
}
