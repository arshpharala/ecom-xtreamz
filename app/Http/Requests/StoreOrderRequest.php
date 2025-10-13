<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

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
            'payment_method'    => 'required|in:stripe,paypal',
            'card_token'        => 'nullable|string',
            'saved_card_id'     => 'nullable|exists:user_cards,id',
            'saved_address_id'  => 'nullable|exists:addresses,id',
            'name'              => 'required_without:saved_address_id|nullable|string|max:255',
            'phone'             => 'required_without:saved_address_id|nullable|string|max:20',
            'province_id'       => 'required_without:saved_address_id|nullable|exists:provinces,id',
            'city_id'           => 'required_without:saved_address_id|nullable|exists:cities,id',
            'area_id'           => 'required_without:saved_address_id|nullable|exists:areas,id',
            'address'           => 'required_without:saved_address_id|nullable|string|max:1000',
            'landmark'          => 'nullable|string|max:500',
            'email'             => Auth::check() ? 'nullable|email' : 'required|email',

        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->sometimes('card_name', 'required|string', function ($input) {
            return $input->payment_method === 'card' && empty($input->saved_card_id);
        });

        $validator->after(function ($validator) {
            if (!Auth::check() && $this->filled('email')) {
                if (User::where('email', $this->input('email'))->where('is_guest', 0)->exists()) {
                    $validator->errors()->add('email', 'An account already exists with this email. Please log in to continue.');
                }
            }
        });
    }
}
