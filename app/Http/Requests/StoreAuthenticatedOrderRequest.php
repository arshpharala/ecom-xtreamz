<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuthenticatedOrderRequest extends FormRequest
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
            'payment_method'    => 'required|in:card,paypal',
            'card_name'         => 'required_without:saved_card_id|string',
            'card_token'        => 'nullable|string',
            'saved_card_id'     => 'nullable|exists:user_cards,id',
            'saved_address_id'  => 'nullable|exists:addresses,id',
            'name'              => 'required_without:saved_address_id|string|max:255',
            'phone'             => 'required_without:saved_address_id|string|max:20',
            'province_id'       => 'required_without:saved_address_id|nullable|exists:provinces,id',
            'city_id'           => 'required_without:saved_address_id|nullable|exists:cities,id',
            'area_id'           => 'required_without:saved_address_id|nullable|exists:areas,id',
            'address'           => 'required_without:saved_address_id|string|max:1000',
            'landmark'          => 'nullable|string|max:500',
        ];
    }
}
