<?php

namespace App\Http\Controllers\Web\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Store\Product\StoreAdminProductRequest;
use App\Http\Requests\Admin\Update\Product\UpdateAdminProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CategoryType;
use App\Models\Color;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'brand', 'colors', 'sizes', 'materials', 'productImages'])
            ->filter($request->all())
            ->paginate(20)
            ->withQueryString();

        $categories = Category::all();
        $brands = Brand::all();

        return Inertia::render('admin/product/Index', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'filters' => $request->only(['search', 'category', 'brand', 'status', 'sort', 'order']),
        ]);
    }

    public function create()
    {
        $categories = Category::with(['categoryTypes'])->get();
        $brands = Brand::all();
        $categoryTypes = CategoryType::all();
        $colors = Color::all();
        $sizes = Size::all();
        $materials = Material::all();

        return Inertia::render('admin/product/Create', [
            'categories' => $categories,
            'brands' => $brands,
            'categoryTypes' => $categoryTypes,
            'colors' => $colors,
            'sizes' => $sizes,
            'materials' => $materials,
        ]);
    }

    public function store(StoreAdminProductRequest $request)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['is_available'] = $data['is_available'] ?? ($data['product_count'] > 0);

        $product = Product::create($data);

        // Обработка изображений
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => Storage::url($path),
                    'is_primary' => false,
                ]);
            }
        }

        // Синхронизация характеристик
        if (!empty($data['colors'])) {
            $product->colors()->sync($data['colors']);
        }
        if (!empty($data['sizes'])) {
            $product->sizes()->sync($data['sizes']);
        }
        if (!empty($data['materials'])) {
            $product->materials()->sync($data['materials']);
        }

        return redirect()->route('admin.product.index')
            ->with('success', "Товар '{$product->name}' успешно создан");
    }

    public function edit(Product $product)
    {
        $product->load(['colors', 'sizes', 'materials', 'productImages']);

        $categories = Category::all();
        $brands = Brand::all();
        $categoryTypes = CategoryType::all();
        $colors = Color::all();
        $sizes = Size::all();
        $materials = Material::all();

        return Inertia::render('admin/product/Edit', [
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
            'categoryTypes' => $categoryTypes,
            'colors' => $colors,
            'sizes' => $sizes,
            'materials' => $materials,
        ]);
    }

    public function update(UpdateAdminProductRequest $request, Product $product)
    {
        $data = $request->validated();

        $data['is_available'] = $data['is_available'] ?? ($data['product_count'] > 0);

        $product->update($data);

        // Удаление выбранных изображений
        if (!empty($data['deleted_images'])) {
            $imagesToDelete = ProductImage::whereIn('id', $data['deleted_images'])->get();
            foreach ($imagesToDelete as $image) {
                $oldPath = str_replace('/storage/', '', $image->image_path);
                Storage::disk('public')->delete($oldPath);
                $image->delete();
            }
        }

        // Добавление новых изображений
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => Storage::url($path),
                    'is_primary' => false,
                ]);
            }
        }

        // Синхронизация характеристик
        if (isset($data['colors'])) {
            $product->colors()->sync($data['colors']);
        }
        if (isset($data['sizes'])) {
            $product->sizes()->sync($data['sizes']);
        }
        if (isset($data['materials'])) {
            $product->materials()->sync($data['materials']);
        }

        return redirect()->route('admin.product.index')
            ->with('success', "Товар '{$product->name}' успешно обновлен");
    }

    public function destroy(Product $product)
    {
        $productName = $product->name;

        // Удаляем все изображения товара
        foreach ($product->productImages as $image) {
            $oldPath = str_replace('/storage/', '', $image->image_path);
            Storage::disk('public')->delete($oldPath);
        }

        $product->delete();

        return redirect()->route('admin.product.index')
            ->with('success', "Товар '{$productName}' успешно удален");
    }

    public function duplicate(Product $product)
    {
        $newProduct = $product->replicate();
        $newProduct->name = $product->name . ' (копия)';
        $newProduct->slug = Str::slug($product->name . '-copy-' . uniqid());
        $newProduct->save();

        // Копируем изображения
        foreach ($product->productImages as $image) {
            ProductImage::create([
                'product_id' => $newProduct->id,
                'image_path' => $image->image_path,
                'is_primary' => $image->is_primary,
            ]);
        }

        // Копируем связи
        $newProduct->colors()->sync($product->colors->pluck('id'));
        $newProduct->sizes()->sync($product->sizes->pluck('id'));
        $newProduct->materials()->sync($product->materials->pluck('id'));

        return redirect()->route('admin.product.index')
            ->with('success', "Товар '{$newProduct->name}' успешно скопирован");
    }

    public function toggleAvailability(Product $product)
    {
        $product->update([
            'is_available' => !$product->is_available
        ]);

        $status = $product->is_available ? 'доступен' : 'недоступен';

        return back()->with('success', "Товар '{$product->name}' теперь {$status}");
    }
}
