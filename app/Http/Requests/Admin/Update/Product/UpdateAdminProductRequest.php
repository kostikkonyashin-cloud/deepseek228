<?php

namespace App\Http\Requests\Admin\Update\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminProductRequest extends FormRequest
{
    public function rules(): array
    {
        $productId = $this->route('product')->id ?? $this->product_id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:products,slug,' . $productId],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'integer', 'min:0', 'max:100'],
            'product_count' => ['required', 'integer', 'min:0'],
            'is_available' => ['boolean'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'category_type_id' => ['nullable', 'exists:category_types,id'],
            'colors' => ['array'],
            'colors.*' => ['exists:colors,id'],
            'sizes' => ['array'],
            'sizes.*' => ['exists:sizes,id'],
            'materials' => ['array'],
            'materials.*' => ['exists:materials,id'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'deleted_images' => ['nullable', 'array'],
            'deleted_images.*' => ['exists:product_images,id'],
        ];
    }
}
