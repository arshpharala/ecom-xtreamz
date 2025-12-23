<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku'   => 'required|string|max:255|unique:product_variants,sku',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'is_primary' => 'nullable|boolean',

            // Attributes OPTIONAL
            'attributes'    => 'nullable|array',
            'attributes.*'  => 'nullable|exists:attribute_values,id',

            // Tags OPTIONAL
            'tags'          => 'nullable|array',
            'tags.*'        => 'nullable|exists:tags,id',

            // Packaging OPTIONAL
            'packaging'     => 'nullable|array',
            'packaging.*'   => 'nullable|string|max:255',
        ];
    }
}
