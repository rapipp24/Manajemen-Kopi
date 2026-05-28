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
                Rule::unique('products', 'name')
                    ->ignore($this->route('product'))
                    ->where(fn ($query) => $query
                        ->where('product_category_id', $this->input('product_category_id'))
                        ->where('weight', $this->input('weight'))
                        ->where('unit_id', $this->input('unit_id'))
                        ->whereNull('deleted_at')
                    ),
                'regex:/^[a-zA-Z0-9\s\.\(\)\-]+$/'
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

    public function messages(): array
    {
        return [
            'name.unique' => 'Produk dengan nama, jenis, berat, dan satuan yang sama sudah ada.',
        ];
    }
}
