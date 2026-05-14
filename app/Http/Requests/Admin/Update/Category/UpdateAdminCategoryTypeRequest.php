<?php

namespace App\Http\Requests\Admin\Update\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminCategoryTypeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'exists:category_types,slug'],
            'category_id' => ['required', 'exists:categories,id'],
            'is_active' => ['boolean'],
        ];
    }
}
