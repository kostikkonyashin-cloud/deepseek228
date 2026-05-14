<?php

namespace App\Http\Requests\Admin\Store\Brand;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminBrandRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:brands,name'],
            'image_path' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048'],
            'is_active' => ['boolean'],
        ];
    }
}
