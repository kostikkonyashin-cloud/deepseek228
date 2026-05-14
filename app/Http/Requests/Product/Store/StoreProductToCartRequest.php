<?php

namespace App\Http\Requests\Product\Store;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductToCartRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99|numeric',
            'size_id' => 'nullable|integer|exists:sizes,id',
            'color_id' => 'nullable|integer|exists:colors,id',
            'material_id' => 'nullable|integer|exists:materials,id',
        ];
    }
}
