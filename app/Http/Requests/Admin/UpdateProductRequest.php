<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')->id;
        return [
            'code' => [
                'required', 
                'string', 
                'max:20', 
                'unique:products,code,' . $productId, 
                'regex:/^[A-Z0-9\-]+$/'
            ],
            'name' => [
                'required', 
                'string', 
                'min:3', 
                'max:50', 
                'regex:/^[a-zA-Z\s\.]+$/'
            ],
            'product_category_id' => [
                'required',
                'exists:product_categories,id',
            ],
            'variant' => 'nullable|string|max:50',
            'weight' => 'required|integer|min:1',
            'unit_id' => 'required|exists:units,id',
            'cost_price' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ];
    }
}
