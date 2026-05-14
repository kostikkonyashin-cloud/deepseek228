<?php

namespace App\Http\Requests\Admin\Update\Brand;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminBrandRequest extends FormRequest
{
    public function rules(): array
    {
        $brandId = $this->route('brand')->id ?? $this->brand_id;

        return [
            'name' => ['required', 'string', 'max:255', 'unique:brands,name,' . $brandId],
            'slug' => ['required', 'string', 'max:255', 'unique:brands,slug,' . $brandId],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048'],
            'is_active' => ['boolean'],
        ];
    }
}
