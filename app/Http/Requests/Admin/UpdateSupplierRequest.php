<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
                'max:100', 
                'regex:/^[a-zA-Z0-9\s\.,&]+$/'
            ],
            'phone' => [
                'required', 
                'string', 
                'max:15', 
                'regex:/^[0-9\+\-\s]+$/'
            ],
            'address' => 'required|string|min:5|max:500',
            'contact_person' => [
                'nullable', 
                'string', 
                'min:2', 
                'max:50', 
                'regex:/^[a-zA-Z\s\.]+$/'
            ],
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ];
    }
}
