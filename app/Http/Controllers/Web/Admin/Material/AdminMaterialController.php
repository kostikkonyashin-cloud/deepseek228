<?php

namespace App\Http\Controllers\Web\Admin\Material;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Store\Material\StoreAdminMaterialRequest;
use App\Http\Requests\Admin\Update\Material\UpdateAdminMaterialRequest;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AdminMaterialController extends Controller
{
    public function index(Request $request)
    {
        $materials = Material::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->when($request->status === 'active', fn($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn($q) => $q->where('is_active', false))
            ->orderBy($request->sort ?? 'created_at', $request->order ?? 'desc')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('admin/material/Index', [
            'materials' => $materials,
            'filters' => $request->only(['search', 'status', 'sort', 'order']),
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/material/Create');
    }

    public function store(StoreAdminMaterialRequest $request)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['is_active'] = $data['is_active'] ?? true;

        Material::create($data);

        return redirect()->route('admin.material.index')
            ->with('success', 'Материал успешно создан');
    }

    public function edit(Material $material)
    {
        return Inertia::render('admin/material/Edit', [
            'material' => $material,
        ]);
    }

    public function update(UpdateAdminMaterialRequest $request, Material $material)
    {
        $data = $request->validated();
        $material->update($data);

        return redirect()->route('admin.material.index')
            ->with('success', 'Материал успешно обновлен');
    }

    public function destroy(Material $material)
    {
        $materialName = $material->name;

        if ($material->products()->count() > 0) {
            return back()->with('error', "Невозможно удалить материал '{$materialName}', так как он используется в товарах");
        }

        $material->delete();

        return redirect()->route('admin.material.index')
            ->with('success', "Материал '{$materialName}' успешно удален");
    }

    public function toggleActive(Material $material)
    {
        $material->update([
            'is_active' => !$material->is_active
        ]);

        $status = $material->is_active ? 'активирован' : 'деактивирован';

        return back()->with('success', "Материал '{$material->name}' успешно {$status}");
    }
}
