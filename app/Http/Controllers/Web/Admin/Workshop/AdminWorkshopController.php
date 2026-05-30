<?php

namespace App\Http\Controllers\Web\Admin\Workshop;

use App\Http\Controllers\Controller;
use App\Models\WorkshopService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminWorkshopController extends Controller
{
    public function index()
    {
        $services = WorkshopService::orderBy('id')->paginate(10);

        return Inertia::render('admin/workshop/Index', [
            'services' => $services
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/workshop/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['title', 'description', 'price']);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('public')->putFileAs('images/workshop', $file, $filename);

            if ($path) {
                $data['image_path'] = '/storage/' . $path;
            }
        }

        WorkshopService::create($data);

        return redirect()->route('admin.workshop.index')
            ->with('success', 'Услуга успешно добавлена');
    }

    public function edit($id)
    {
        $service = WorkshopService::findOrFail($id);

        return Inertia::render('admin/workshop/Edit', [
            'service' => $service
        ]);
    }

    public function update(Request $request, $id)
    {
        $service = WorkshopService::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['title', 'description', 'price']);

        if ($request->hasFile('image')) {
            if ($service->image_path) {
                $oldPath = str_replace('/storage/', '', $service->image_path);
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('public')->putFileAs('images/workshop', $file, $filename);

            if ($path) {
                $data['image_path'] = '/storage/' . $path;
            }
        }

        $service->update($data);

        return redirect()->route('admin.workshop.index')
            ->with('success', 'Услуга успешно обновлена');
    }

    public function destroy($id)
    {
        $service = WorkshopService::findOrFail($id);

        if ($service->image_path) {
            $oldPath = str_replace('/storage/', '', $service->image_path);
            Storage::disk('public')->delete($oldPath);
        }

        $service->delete();

        return redirect()->route('admin.workshop.index')
            ->with('success', 'Услуга удалена');
    }

    public function publicIndex()
    {
        $services = WorkshopService::get();

        return Inertia::render('workshop/WorkshopPage', [
            'services' => $services
        ]);
    }
}
