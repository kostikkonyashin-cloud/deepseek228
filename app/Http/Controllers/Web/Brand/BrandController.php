<?php

namespace App\Http\Controllers\Web\Brand;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::get();

        return Inertia::render('brand/IndexPage', compact(['brands']));
    }

    public function show(Request $request, string $brand)
    {
        // Товары
        $products = Product::with(['brand', 'category'])->filter($request->all())->whereHas('brand', function (Builder $query) use ($brand) {
            $query->where('slug', $brand);
        })->paginate(15);

        $categories = Category::get();

        // Фильтры
        $min_price = Product::whereHas('brand', function ($query) use ($brand) {
            $query->where('slug', $brand);
        })->min('price');
        $max_price = Product::whereHas('brand', function ($query) use ($brand) {
            $query->where('slug', $brand);
        })->max('price');
        $filters = $request->all();

        // Название бренда
        $brand_data = Brand::where('slug', $brand)->first();

        return Inertia::render('brand/ShowPage', compact(['products', 'filters', 'min_price', 'max_price', 'brand_data', 'categories']));
    }
}
