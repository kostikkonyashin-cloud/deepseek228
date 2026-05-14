<?php

namespace App\Http\Requests\Product\Update;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductQuantityToCartRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $cartItem = Cart::find($this->route('id'));

            if ($cartItem) {
                $product = Product::find($cartItem->product_id);
                $newQuantity = $this->input('quantity');

                if ($newQuantity > $product->product_count) {
                    $validator->errors()->add('quantity', "Доступно только {$product->product_count} шт.");
                }
            }
        });
    }
}
