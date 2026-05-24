<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => trim($this->input('name')),
            ]);
        }

        // Normalkan is_active ke boolean
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => (bool) $this->input('is_active'),
            ]);
        } else {
            $this->merge(['is_active' => true]);
        }
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:50'],
            'note'      => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama karyawan wajib diisi.',
            'name.max'      => 'Nama karyawan maksimal 255 karakter.',
            'phone.max'     => 'No HP maksimal 50 karakter.',
        ];
    }
}
