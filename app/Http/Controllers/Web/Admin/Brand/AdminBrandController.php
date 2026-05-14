<?php

namespace App\Http\Controllers\Web\Admin\Brand;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Store\Brand\StoreAdminBrandRequest;
use App\Http\Requests\Admin\Update\Brand\UpdateAdminBrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AdminBrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->when($request->status === 'active', fn($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn($q) => $q->where('is_active', false))
            ->orderBy($request->sort ?? 'created_at', $request->order ?? 'desc')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('admin/brand/Index', [
            'brands' => $brands,
            'filters' => $request->only(['search', 'status', 'sort', 'order']),
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/brand/Create');
    }

    public function store(StoreAdminBrandRequest $request)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['is_active'] = $data['is_active'] ?? true;

        // Обработка загрузки изображения
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('brands', 'public');
            $data['image_path'] = Storage::url($path);
        }

        $brand = Brand::create($data);

        return redirect()->route('admin.brand.index')
            ->with('success', "Бренд '{$brand->name}' успешно создан");
    }

    public function edit(Brand $brand)
    {
        return Inertia::render('admin/brand/Edit', [
            'brand' => $brand,
        ]);
    }

    public function update(UpdateAdminBrandRequest $request, Brand $brand)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($brand->image_path) {
                $oldPath = str_replace('/storage/', '', $brand->image_path);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('brands', 'public');
            $data['image_path'] = Storage::url($path);
        }

        $brand->update($data);

        return redirect()->route('admin.brand.index')
            ->with('success', "Бренд '{$brand->name}' успешно обновлен");
    }

    public function destroy(Brand $brand)
    {
        $brandName = $brand->name;

        if ($brand->products()->count() > 0) {
            return back()->with('error', "Невозможно удалить бренд '{$brandName}', так как к нему привязаны товары");
        }

        // Удаляем изображение бренда
        if ($brand->image_path) {
            $oldPath = str_replace('/storage/', '', $brand->image_path);
            Storage::disk('public')->delete($oldPath);
        }

        $brand->delete();

        return redirect()->route('admin.brand.index')
            ->with('success', "Бренд '{$brandName}' успешно удален");
    }

    public function toggleActive(Brand $brand)
    {
        $brand->update([
            'is_active' => !$brand->is_active
        ]);

        $status = $brand->is_active ? 'активирован' : 'деактивирован';

        return back()->with('success', "Бренд '{$brand->name}' успешно {$status}");
    }
}
