<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Middleware handles auth
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
                'required', // Sekarang Wajib
                'string', 
                'max:15', 
                'regex:/^[0-9\+\-\s]+$/'
            ],
            'address' => 'required|string|min:5|max:500', // Sekarang Wajib
            'contact_person' => [
                'required', // Sekarang Wajib
                'string', 
                'min:2', 
                'max:50', 
                'regex:/^[a-zA-Z\s\.]+$/'
            ],
        ];
    }
}
