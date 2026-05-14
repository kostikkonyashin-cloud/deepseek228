<?php

namespace App\Http\Controllers\Web\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Store\User\StoreAdminUserRequest;
use App\Http\Requests\Admin\Update\User\UpdateAdminUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('role')
            ->when($request->search, function ($query, $search) {
                $query->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('login', 'like', "%{$search}%");
            })
            ->when($request->role, function ($query, $role) {
                $query->whereHas('role', fn($q) => $q->where('slug', $role));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $roles = Role::all();

        return Inertia::render('admin/user/Index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => $request->only(['search', 'role']),
        ]);
    }

    public function create()
    {
        $roles = Role::all();

        return Inertia::render('admin/user/Create', [
            'roles' => $roles,
        ]);
    }

    public function store(StoreAdminUserRequest $request)
    {
        $user = User::create([
            'full_name' => $request->full_name,
            'login' => $request->login,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('admin.user.index')
            ->with('success', "Пользователь {$user->full_name} успешно создан");
    }

    public function edit(User $user)
    {
        $roles = Role::all();

        return Inertia::render('admin/user/Edit', [
            'user' => $user->load('role'),
            'roles' => $roles,
        ]);
    }

    public function update(UpdateAdminUserRequest $request, User $user)
    {
        $data = [
            'full_name' => $request->full_name,
            'login' => $request->login,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.user.index')
            ->with('success', "Пользователь {$user->full_name} успешно обновлен");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Вы не можете удалить свой собственный аккаунт');
        }

        $userName = $user->full_name;
        $user->delete();

        return redirect()->route('admin.user.index')
            ->with('success', "Пользователь {$userName} успешно удален");
    }

    public function toggleBlock(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Вы не можете заблокировать свой собственный аккаунт');
        }

        $user->update([
            'is_blocked' => !$user->is_blocked
        ]);

        $status = $user->is_blocked ? 'заблокирован' : 'разблокирован';

        return back()->with('success', "Пользователь {$user->full_name} успешно {$status}");
    }
}
