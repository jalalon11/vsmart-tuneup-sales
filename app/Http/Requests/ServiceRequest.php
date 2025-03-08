<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The service name is required.',
            'price.required' => 'Please enter the service price.',
            'price.min' => 'The price must be greater than or equal to 0.',
            'category_id.exists' => 'The selected category does not exist.',
        ];
    }
} 