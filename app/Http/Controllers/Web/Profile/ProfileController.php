<?php

namespace App\Http\Controllers\Web\Profile;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderItems', 'orderItems.product', 'orderItems.product.category', 'orderItems.product.brand', 'orderItems.size', 'orderItems.material', 'orderItems.color'])->where('user_id', auth()->id())->orderByDesc('ordered_at')->get();

        return Inertia::render('profile/ProfilePage', compact(['orders']));
    }

    public function destroy()
    {
        Auth::logout();

        return redirect()->route('index');
    }
}
