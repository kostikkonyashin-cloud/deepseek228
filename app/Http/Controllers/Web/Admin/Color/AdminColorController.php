<?php

namespace App\Http\Controllers\Web\Admin\Color;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Store\Color\StoreAdminColorRequest;
use App\Http\Requests\Admin\Update\Color\UpdateAdminColorRequest;
use App\Models\Color;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Str;

class AdminColorController extends Controller
{
    public function index(Request $request)
    {
        $colors = Color::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->orderBy($request->sort ?? 'created_at', $request->order ?? 'desc')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('admin/color/Index', [
            'colors' => $colors,
            'filters' => $request->only(['search', 'sort', 'order']),
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/color/Create');
    }

    public function store(StoreAdminColorRequest $request)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        Color::create($data);

        return redirect()->route('admin.color.index')
            ->with('success', 'Цвет успешно создан');
    }

    public function edit(Color $color)
    {
        return Inertia::render('admin/color/Edit', [
            'color' => $color,
        ]);
    }

    public function update(UpdateAdminColorRequest $request, Color $color)
    {
        $data = $request->validated();
        $color->update($data);

        return redirect()->route('admin.color.index')
            ->with('success', 'Цвет успешно обновлен');
    }

    public function destroy(Color $color)
    {
        $colorName = $color->name;

        if ($color->products()->count() > 0) {
            return back()->with('error', "Невозможно удалить цвет '{$colorName}', так как он используется в товарах");
        }

        $color->delete();

        return redirect()->route('admin.color.index')
            ->with('success', "Цвет '{$colorName}' успешно удален");
    }
}
