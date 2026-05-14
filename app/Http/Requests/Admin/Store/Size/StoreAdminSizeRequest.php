<?php

namespace App\Http\Requests\Admin\Store\Size;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminSizeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:sizes,name'],
            'slug' => ['required', 'string', 'max:255', 'unique:sizes,slug'],
            'size_type_id' => ['nullable', 'exists:size_types,id'],
            'is_active' => ['boolean'],
        ];
    }
}
