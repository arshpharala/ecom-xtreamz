<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class StoreOrderRequest extends FormRequest
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
            'payment_method' => 'required|in:stripe,paypal,mashreq,touras',
            'card_token' => 'nullable|string',
            'saved_card_id' => 'nullable|exists:user_cards,id',

            'saved_address_id' => 'nullable|exists:addresses,id',
            'name' => 'required_without:saved_address_id|nullable|string|max:255',
            'phone' => 'required_without:saved_address_id|nullable|string|max:20',
            'province_id' => 'required_without:saved_address_id|nullable|exists:provinces,id',
            'city_id' => 'required_without:saved_address_id|nullable|exists:cities,id',
            // 'area_id' => 'required_without:saved_address_id|nullable|exists:areas,id',
            'address' => 'required_without:saved_address_id|nullable|string|max:1000',
            'landmark' => 'nullable|string|max:500',

            'shipping_name' => 'nullable|string|max:255|required_with:shipping_phone,shipping_province_id,shipping_city_id,shipping_address',
            'shipping_phone' => 'nullable|string|max:20|required_with:shipping_name,shipping_province_id,shipping_city_id,shipping_address',
            'shipping_province_id' => 'nullable|exists:provinces,id|required_with:shipping_name,shipping_phone,shipping_city_id,shipping_address',
            'shipping_city_id' => 'nullable|exists:cities,id|required_with:shipping_name,shipping_phone,shipping_province_id,shipping_address',
            'shipping_address' => 'nullable|string|max:1000|required_with:shipping_name,shipping_phone,shipping_province_id,shipping_city_id',
            'shipping_landmark' => 'nullable|string|max:500',

            // Shipping pin is mandatory even when shipping address is same as billing.
            'shipping_map_latitude' => 'required|numeric|between:-90,90',
            'shipping_map_longitude' => 'required|numeric|between:-180,180',
            'shipping_map_url' => 'nullable|url|max:2048',

            'email' => Auth::check() ? 'nullable|email' : 'required|email',

            // Touras Card Details - HOSTED MODE (Empty)
            'card_no' => 'nullable',
            'exp_month' => 'nullable',
            'exp_year' => 'nullable',
            'cvv2' => 'nullable',
            'card_name' => 'nullable',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->sometimes('card_name', 'required|string', function ($input) {
            return $input->payment_method === 'card' && empty($input->saved_card_id);
        });

        $validator->after(function ($validator) {
            if (! Auth::check() && $this->filled('email')) {
                if (User::where('email', $this->input('email'))->where('is_guest', 0)->exists()) {
                    $validator->errors()->add('email', 'An account already exists with this email. Please log in to continue.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'name.required_without' => 'Please enter your full name.',
            'phone.required_without' => 'Please provide a phone number.',
            'province_id.required_without' => 'Please select a province.',
            'city_id.required_without' => 'Please select a city.',
            // 'area_id.required_without' => 'Please select an area.',
            'address.required_without' => 'Please provide your address details.',

            'shipping_name.required_with' => 'Please enter shipping recipient name.',
            'shipping_phone.required_with' => 'Please provide shipping phone number.',
            'shipping_province_id.required_with' => 'Please select shipping province.',
            'shipping_city_id.required_with' => 'Please select shipping city.',
            'shipping_address.required_with' => 'Please provide shipping address details.',
            'shipping_map_latitude.required' => 'Please pin your shipping location on the map.',
            'shipping_map_longitude.required' => 'Please pin your shipping location on the map.',

            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
        ];
    }
}
