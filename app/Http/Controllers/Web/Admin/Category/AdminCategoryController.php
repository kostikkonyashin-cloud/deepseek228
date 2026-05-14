<?php

namespace App\Http\Controllers\Web\Admin\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Store\Category\StoreAdminCategoryRequest;
use App\Http\Requests\Admin\Store\Category\StoreAdminCategoryTypeRequest;
use App\Http\Requests\Admin\Update\Category\UpdateAdminCategoryRequest;
use App\Http\Requests\Admin\Update\Category\UpdateAdminCategoryTypeRequest;
use App\Models\Category;
use App\Models\CategoryType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AdminCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->withCount('categoryTypes')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->when($request->status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($request->status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->orderBy($request->sort ?? 'created_at', $request->order ?? 'desc')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('admin/category/Index', [
            'product_categories' => $categories,
            'filters' => $request->only(['search', 'status', 'sort', 'order']),
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/category/Create');
    }

    public function store(StoreAdminCategoryRequest $request)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['is_active'] = $data['is_active'] ?? true;

        $category = Category::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'icon_path' => $data['icon_path'],
            'is_active' => $data['is_active'],
        ]);

        return redirect()->route('admin.category.index')
            ->with('success', "Категория '{$category->name}' успешно создана");
    }

    public function edit(Category $category)
    {
        return Inertia::render('admin/category/Edit', [
            'category' => $category,
        ]);
    }

    public function update(UpdateAdminCategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $category->update($data);

        return redirect()->route('admin.category.index')
            ->with('success', "Категория '{$category->name}' успешно обновлена");
    }

    public function destroy(Category $category)
    {
        $categoryName = $category->name;

        if ($category->products()->count() > 0) {
            return back()->with('error', "Невозможно удалить категорию '{$categoryName}', так как к ней привязаны товары");
        }

        $category->delete();

        return redirect()->route('admin.category.index')
            ->with('success', "Категория '{$categoryName}' успешно удалена");
    }

    public function toggleActive(Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        $status = $category->is_active ? 'активирована' : 'деактивирована';

        return back()->with('success', "Категория '{$category->name}' успешно {$status}");
    }

    public function typesIndex(Request $request)
    {
        $types = CategoryType::query()
            ->with('category')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->when($request->category, function ($query, $category) {
                $query->whereHas('category', fn($q) => $q->where('slug', $category));
            })
            ->when($request->status === 'active', fn($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn($q) => $q->where('is_active', false))
            ->orderBy($request->sort ?? 'created_at', $request->order ?? 'desc')
            ->paginate(20)
            ->withQueryString();

        $categories = Category::where('is_active', true)->get();

        return Inertia::render('admin/category/categoryTypes/Index', [
            'types' => $types,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category', 'status', 'sort', 'order']),
        ]);
    }

    public function typesCreate()
    {
        $categories = Category::where('is_active', true)->get();

        return Inertia::render('admin/category/categoryTypes/Create', [
            'categories' => $categories,
        ]);
    }

    public function typesStore(StoreAdminCategoryTypeRequest $request)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['is_active'] = $data['is_active'] ?? true;

        $type = CategoryType::create($data);

        return redirect()->route('admin.category.types.index')
            ->with('success', "Подкатегория '{$type->name}' успешно создана");
    }

    public function typesEdit(CategoryType $categoryType)
    {
        $categories = Category::where('is_active', true)->get();

        return Inertia::render('admin/category/categoryTypes/Edit', [
            'type' => $categoryType->load('category'),
            'categories' => $categories,
        ]);
    }

    public function typesUpdate(UpdateAdminCategoryTypeRequest $request, CategoryType $categoryType)
    {
        try {
            $data = $request->validated();
            $categoryType->update($data);

            return redirect()->route('admin.category.types.index')
                ->with('success', "Подкатегория '{$categoryType->name}' успешно обновлена");
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function typesDestroy(CategoryType $categoryType)
    {
        $typeName = $categoryType->name;

        if ($categoryType->products()->count() > 0) {
            return back()->with('error', "Невозможно удалить подкатегорию '{$typeName}', так как к ней привязаны товары");
        }

        $categoryType->delete();

        return redirect()->route('admin.category.types.index')
            ->with('success', "Подкатегория '{$typeName}' успешно удалена");
    }

    public function typesToggleActive(CategoryType $categoryType)
    {
        $categoryType->update([
            'is_active' => !$categoryType->is_active
        ]);

        $status = $categoryType->is_active ? 'активирована' : 'деактивирована';

        return back()->with('success', "Подкатегория '{$categoryType->name}' успешно {$status}");
    }
}
