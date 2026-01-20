<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponRequest extends FormRequest
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
        $id = $this->route('coupon');

        return [
            'code' => 'required|string|unique:coupons,code,' . $id,
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'min_cart_amount' => 'nullable|numeric|min:0',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after:start_at',
            'max_usage' => 'nullable|integer|min:0',
            'max_usage_per_user' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'first_time_only' => 'boolean',
            'product_variant_ids' => 'array',
            'product_variant_ids.*' => 'uuid|exists:product_variants,id'
        ];
    }
}
