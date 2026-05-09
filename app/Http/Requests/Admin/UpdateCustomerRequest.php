<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required', 
                'string', 
                'min:3', 
                'max:50', 
                'regex:/^[a-zA-Z\s\.]+$/'
            ],
            'phone' => [
                'nullable', 
                'string', 
                'min:10', 
                'max:15', 
                'regex:/^[0-9]+$/'
            ],
            'address' => 'nullable|string|max:200',
            'is_active' => 'boolean',
        ];
    }
}
