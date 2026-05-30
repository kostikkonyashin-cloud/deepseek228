<?php

namespace App\Http\Controllers\Web\Admin\Member;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminCommandMemberController extends Controller
{
    // Список всех членов команды
    public function index()
    {
        $teamMembers = TeamMember::orderBy('id')->paginate(10);

        return Inertia::render('admin/team/Index', [
            'teamMembers' => $teamMembers
        ]);
    }

    // Страница создания нового члена команды
    public function create()
    {
        return Inertia::render('admin/team/Create');
    }

    // Сохранение нового члена команды
    public function store(Request $request)
    {
        $request->validate([
            'surname' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'position' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['surname', 'name', 'description', 'position']);

        // Обработка изображения
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Сохраняем файл через Storage фасад
            $path = Storage::disk('public')->putFileAs('images/members', $file, $filename);

            if ($path) {
                $data['image_path'] = '/storage/' . $path;
            }
        }

        TeamMember::create($data);

        return redirect()->route('admin.team.index')
            ->with('success', 'Член команды успешно добавлен');
    }

    // Страница редактирования
    public function edit($id)
    {
        $teamMember = TeamMember::findOrFail($id);

        return Inertia::render('admin/team/Edit', [
            'teamMember' => $teamMember
        ]);
    }

    // Обновление данных
    public function update(Request $request, $id)
    {
        $teamMember = TeamMember::findOrFail($id);

        $request->validate([
            'surname' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'position' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['surname', 'name', 'description', 'position']);

        // Обработка нового изображения
        if ($request->hasFile('image')) {
            // Удаляем старое изображение
            if ($teamMember->image_path) {
                $oldPath = str_replace('/storage/', '', $teamMember->image_path);
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('public')->putFileAs('images/members', $file, $filename);

            if ($path) {
                $data['image_path'] = '/storage/' . $path;
            }
        }

        $teamMember->update($data);

        return redirect()->route('admin.team.index')
            ->with('success', 'Данные успешно обновлены');
    }

    // Удаление члена команды
    public function destroy($id)
    {
        $teamMember = TeamMember::findOrFail($id);

        // Удаляем изображение
        if ($teamMember->image_path) {
            $oldPath = str_replace('/storage/images/members/', '', $teamMember->image_path);
            Storage::disk('public')->delete('images/members/' . $oldPath);
        }

        $teamMember->delete();

        return redirect()->route('admin.team.index')
            ->with('success', 'Член команды удален');
    }
}
