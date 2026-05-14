<?php

namespace App\Http\Requests\Admin\Update\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        $categoryId = $this->route('category')->id ?? $this->category_id;

        return [
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $categoryId],
            'slug' => ['required', 'string', 'max:255', 'unique:categories,slug,' . $categoryId],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }

}
