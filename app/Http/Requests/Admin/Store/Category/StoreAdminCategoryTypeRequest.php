<?php

namespace App\Http\Requests\Admin\Store\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminCategoryTypeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'is_active' => ['boolean'],
        ];
    }
}
