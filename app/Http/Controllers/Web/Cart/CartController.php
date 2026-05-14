<?php

namespace App\Http\Controllers\Web\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Exception;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        $cart_products = Cart::with(['product', 'product.category', 'color', 'size', 'material'])->whereHas('user', function ($query) {
            $query->where('email', auth()->user()->email);
        })->get();

        return Inertia::render('cart/CartPage', compact(['cart_products']));
    }

    public function destroy()
    {
        try {
            Cart::where('user_id', auth()->id())->delete();

            return redirect()->back()->with('success', 'Корзина очищена');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
