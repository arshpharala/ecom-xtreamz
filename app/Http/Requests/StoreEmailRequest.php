<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmailRequest extends FormRequest
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
            'reference' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'template' => 'required|string|max:255',
            'from_email' => 'nullable|email|max:255',
            'from_name' => 'nullable|string|max:255',
            'reply_to_email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ];
    }
}
