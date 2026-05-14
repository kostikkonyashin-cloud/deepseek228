<?php

namespace App\Http\Controllers\Web\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Store\StoreProductToCartRequest;
use App\Http\Requests\Product\Update\UpdateProductQuantityToCartRequest;
use App\Models\Cart;
use App\Models\Category;
use App\Models\CategoryType;
use App\Models\Color;
use App\Models\Material;
use App\Models\Product;
use App\Models\Size;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request, string $category)
    {
        // Все продукты
        $products = Product::with(['category', 'brand', 'category.categoryTypes', 'productImages'])
            ->filter($request->all())
            ->whereCategory($category)
            ->orderByDesc('is_available')
            ->paginate(20)
            ->withQueryString();

        // Для фильтров
        $sizes = Size::whereHas('sizeType.category', function ($query) use ($category) {
            $query->where('slug', $category);
        })->get();
        $colors = Color::get();
        $materials = Material::get();
        $filters = $request->all();
        $min_price = Product::whereHas('category', function ($query) use ($category) {
            $query->where('slug', $category);
        })->min('price');
        $max_price = Product::whereHas('category', function ($query) use ($category) {
            $query->where('slug', $category);
        })->max('price');

        // Мета-информация
        $category_types = CategoryType::where('is_active', true)->whereHas('category', function (Builder $query) use ($category) {
            $query->where('slug', $category);
        })->get();

        $category = Category::where('slug', $category)->first();

        return Inertia::render('product/IndexPage', compact(['products', 'category', 'category_types', 'filters', 'sizes', 'colors', 'materials', 'min_price', 'max_price']));
    }

    public function store(StoreProductToCartRequest $request)
    {
        try {
            $product = Product::where('id', $request->product_id)->first();

            if (!$product) {
                throw new Exception('Товар не найден');
            }

            if (!$product->is_available) {
                throw new Exception("Товар '{$product->name}' временно недоступен");
            }

            $existingCartItem = Cart::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->where('size_id', $request->size_id)
                ->where('color_id', $request->color_id)
                ->where('material_id', $request->material_id)
                ->first();

            $currentQuantity = $existingCartItem ? $existingCartItem->quantity : 0;
            $newQuantity = $currentQuantity + $request->quantity;

            if ($newQuantity > $product->product_count) {
                $maxAvailable = $product->product_count - $currentQuantity;
                throw new Exception("Вы можете добавить не более {$maxAvailable} шт. товара '{$product->name}'. В корзине уже {$currentQuantity} шт.");
            }

            if ($existingCartItem) {
                $existingCartItem->update([
                    'quantity' => $newQuantity
                ]);
            } else {
                Cart::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'size_id' => $request->size_id,
                    'color_id' => $request->color_id,
                    'material_id' => $request->material_id,
                ]);
            }

            return redirect()->back()->with('success', 'Товар добавлен в корзину');

        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(string $category, string $product)
    {
        $show_product = Product::with(['category', 'brand', 'sizes', 'colors', 'materials'])->where('slug', $product)->first();

        return Inertia::render('product/ShowPage', [
            'product' => $show_product
        ]);
    }

    public function update(UpdateProductQuantityToCartRequest $request, string $product)
    {
        try {
            $query = Cart::where('user_id', auth()->id())
                ->whereHas('product', function (Builder $query) use ($product) {
                    $query->where('slug', $product);
                });

            if ($request->has('color_id')) {
                $query->where('color_id', $request->color_id);
            } else {
                $query->whereNull('color_id');
            }

            if ($request->has('size_id')) {
                $query->where('size_id', $request->size_id);
            } else {
                $query->whereNull('size_id');
            }

            if ($request->has('material_id')) {
                $query->where('material_id', $request->material_id);
            } else {
                $query->whereNull('material_id');
            }

            $cartItem = $query->firstOrFail();

            $productModel = $cartItem->product;
            $newQuantity = $request->quantity;

            if ($newQuantity > $productModel->product_count) {
                throw new Exception("Извините, доступно только {$productModel->product_count} шт. товара '{$productModel->name}'");
            }

            $cartItem->update(['quantity' => $newQuantity]);

            return redirect()->back()->with('success', 'Количество обновлено');

        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, string $product)
    {
        try {
            $cartItem = Cart::where('user_id', auth()->id())
                ->whereHas('product', function (Builder $query) use ($product) {
                    $query->where('slug', $product);
                })
                ->when($request->has('color_id'), function ($query) use ($request) {
                    $query->where('color_id', $request->color_id);
                })
                ->when($request->has('size_id'), function ($query) use ($request) {
                    $query->where('size_id', $request->size_id);
                })
                ->when($request->has('material_id'), function ($query) use ($request) {
                    $query->where('material_id', $request->material_id);
                })
                ->firstOrFail();

            $cartItem->delete();

            return redirect()->back()->with('success', 'Товар удален из корзины');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
