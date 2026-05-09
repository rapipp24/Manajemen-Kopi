<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreRawMaterialRequest extends FormRequest
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
            'unit_id' => 'required|exists:units,id',
            'minimum_stock' => 'required|numeric|min:0',
        ];
    }
}
