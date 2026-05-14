<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $withAllErrors = true;

    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $auth_status = Auth::check();
        $user_info = $auth_status ? User::with(['role'])->findOrFail(auth()->id()) : [];

        return [
            'user_info' => $user_info,
            'isAuthenticated' => $auth_status,
            'categories' => Category::get(),
            'user_role' => $auth_status ? $user_info->role->name : null,
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
                'warning' => fn() => $request->session()->get('warning'),
                'info' => fn() => $request->session()->get('info'),
            ],
            'errors' => fn() => $request->session()->get('errors')
                ? $request->session()->get('errors')->getBag('default')->getMessages()
                : (object) [],
        ];
    }
}
