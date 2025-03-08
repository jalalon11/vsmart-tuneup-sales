<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'device_brand' => ['nullable', 'string', 'max:255'],
            'device_model' => ['required_with:device_brand', 'nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The customer name is required.',
            'facebook_url.url' => 'Please enter a valid Facebook URL.',
            'email.email' => 'Please enter a valid email address.',
            'phone.required' => 'The phone number is required.',
            'device_model.required_with' => 'The device model is required when brand is provided.',
        ];
    }
} 