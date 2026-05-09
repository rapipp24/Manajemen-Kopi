<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
            'category' => [
                'required',
                Rule::in(['Kopi Standar', 'Kopi Premium']),
            ],
            'variant' => 'nullable|string|max:50',
            'weight' => 'required|integer|min:1',
            'unit_id' => 'required|exists:units,id',
            'cost_price' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ];
    }
}
