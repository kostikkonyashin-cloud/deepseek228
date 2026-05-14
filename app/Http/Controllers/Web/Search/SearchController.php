<?php

namespace App\Http\Controllers\Web\Search;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $products = Product::with(['category', 'brand', 'category.categoryTypes', 'productImages'])
            ->filter($request->all())
            ->orderByDesc('is_available')
            ->paginate(20)
            ->withQueryString();

        $categories = Category::get();

        $min_price = Product::min('price');
        $max_price = Product::max('price');

        $filters = $request->all();

        return Inertia::render('search/SearchPage', compact(['products', 'max_price', 'min_price', 'filters', 'categories']));
    }
}
