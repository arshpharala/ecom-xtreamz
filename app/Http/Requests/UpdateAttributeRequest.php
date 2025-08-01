<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttributeRequest extends FormRequest
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
        $id = $this->route('attribute') ?? $this->route('id');

        return [
            'name' => 'required|string|max:255|unique:attributes,name,' . $id,
            'att_value' => 'required|array|min:1',
            'att_value.*' => 'required|string|max:255'
        ];
    }
}
