<?php

namespace App\Http\Requests\Admin\Store\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'icon_path' => ['nullable', 'string', 'max:255', 'unique:categories,name'],
            'is_active' => ['boolean'],
        ];
    }
}
