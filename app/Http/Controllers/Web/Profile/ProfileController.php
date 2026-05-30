<?php

namespace App\Http\Controllers\Web\Profile;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderItems', 'orderItems.product', 'orderItems.product.category', 'orderItems.product.brand', 'orderItems.size', 'orderItems.material', 'orderItems.color'])->where('user_id', auth()->id())->orderByDesc('ordered_at')->get();

        return Inertia::render('profile/ProfilePage', compact(['orders']));
    }

    public function edit()
    {
        $user = Auth::user();

        return Inertia::render('profile/EditPage', [
            'user' => $user
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'login' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($validated);

        return redirect()->route('profile.index')->with('success', 'Логин успешно обновлен');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Пароль успешно изменен');
    }

    public function destroy()
    {
        Auth::logout();

        return redirect()->route('index');
    }
}
