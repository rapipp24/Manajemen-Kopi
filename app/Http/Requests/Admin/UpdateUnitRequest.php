<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:50',
            'code' => 'required|string|min:1|max:10|unique:units,code,' . $this->unit->id . ',id,deleted_at,NULL',
            'type' => 'required|in:bahan_baku,produk,semua',
            'is_active' => 'boolean',
        ];
    }
}
