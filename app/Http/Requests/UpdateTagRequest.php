<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTagRequest extends FormRequest
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
        $id = $this->route('tag') ?? $this->route('id');

        return [
            'name'      => 'required|string|max:255|unique:tags,,name,' . $id . ',id',
            'position'  => 'nullable|integer',
            'is_visible' => 'boolean'
        ];
    }
}
