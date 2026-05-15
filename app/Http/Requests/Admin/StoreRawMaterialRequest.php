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
                'unique:raw_materials,name,NULL,id,deleted_at,NULL',
                'regex:/^[a-zA-Z0-9\s\.\(\)\-]+$/'
            ],
            'unit_id' => 'required|exists:units,id',
            'minimum_stock' => 'required|numeric|min:0',
        ];
    }
}
