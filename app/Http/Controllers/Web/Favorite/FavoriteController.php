<?php

namespace App\Http\Controllers\Web\Favorite;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Auth::user()->favorites()
            ->with(['category', 'brand'])
            ->paginate(12);

        // Добавляем статус избранного для каждого товара
        $favorites->getCollection()->each(function ($product) {
            $product->is_favorited = true;
        });

        return Inertia::render('profile/FavoritePage', [
            'favorites' => $favorites
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $exists = Favorite::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->exists();

        if (!$exists) {
            Favorite::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id
            ]);

            return redirect()->back()->with('success', 'Товар добавлен в избранное');
        }

        return redirect()->back()->with('info', 'Товар уже в избранном');
    }

    public function destroy($productId)
    {
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            $favorite->delete();

            return redirect()->back()->with('success', 'Товар удален из избранного');
        }

        return redirect()->back()->with('warning', 'Товар не найден в избранном');
    }

    public function check($productId)
    {
        $isFavorited = Favorite::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();

        // Возвращаем через Inertia с рендером или редиректом
        return redirect()->back()->with('is_favorited', $isFavorited);
    }

    public function getFavoritesIds()
    {
        $favoriteIds = Auth::user()->favorites()->pluck('product_id');

        // Передаем через сессию или редирект
        return redirect()->back()->with('favorite_ids', $favoriteIds);
    }
}
