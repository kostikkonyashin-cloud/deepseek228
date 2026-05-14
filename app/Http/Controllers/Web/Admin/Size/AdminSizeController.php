<?php

namespace App\Http\Controllers\Web\Admin\Size;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Store\Size\StoreAdminSizeRequest;
use App\Http\Requests\Admin\Update\Size\UpdateAdminSizeRequest;
use App\Models\Size;
use App\Models\SizeType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AdminSizeController extends Controller
{
    public function index(Request $request)
    {
        $sizes = Size::query()
            ->with(['sizeType', 'sizeType.category'])
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->when($request->type, function ($query, $type) {
                $query->whereHas('sizeType', fn($q) => $q->where('slug', $type));
            })
            ->when($request->status === 'active', fn($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn($q) => $q->where('is_active', false))
            ->orderBy($request->sort ?? 'created_at', $request->order ?? 'desc')
            ->paginate(20)
            ->withQueryString();

        $sizeTypes = SizeType::with('category')->get();

        return Inertia::render('admin/size/Index', [
            'sizes' => $sizes,
            'sizeTypes' => $sizeTypes,
            'filters' => $request->only(['search', 'type', 'status', 'sort', 'order']),
        ]);
    }

    /**
     * Форма создания размера
     */
    public function create()
    {
        $sizeTypes = SizeType::with('category')->get();

        return Inertia::render('Admin/Sizes/Create', [
            'sizeTypes' => $sizeTypes,
        ]);
    }

    /**
     * Сохранение размера
     */
    public function store(StoreAdminSizeRequest $request)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['is_active'] = $data['is_active'] ?? true;

        Size::create($data);

        return redirect()->route('admin.size.index')
            ->with('success', 'Размер успешно создан');
    }

    /**
     * Форма редактирования размера
     */
    public function edit(Size $size)
    {
        $sizeTypes = SizeType::with('category')->get();

        return Inertia::render('Admin/Sizes/Edit', [
            'size' => $size->load('sizeType'),
            'sizeTypes' => $sizeTypes,
        ]);
    }

    /**
     * Обновление размера
     */
    public function update(UpdateAdminSizeRequest $request, Size $size)
    {
        $data = $request->validated();
        $size->update($data);

        return redirect()->route('admin.size.index')
            ->with('success', 'Размер успешно обновлен');
    }

    /**
     * Удаление размера
     */
    public function destroy(Size $size)
    {
        $sizeName = $size->name;

        // Проверяем, есть ли товары с этим размером
        if ($size->products()->count() > 0) {
            return back()->with('error', "Невозможно удалить размер '{$sizeName}', так как он используется в товарах");
        }

        $size->delete();

        return redirect()->route('admin.size.index')
            ->with('success', "Размер '{$sizeName}' успешно удален");
    }

    public function toggleActive(Size $size)
    {
        $size->update([
            'is_active' => !$size->is_active
        ]);

        $status = $size->is_active ? 'активирован' : 'деактивирован';

        return back()->with('success', "Размер '{$size->name}' успешно {$status}");
    }
}
