<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RegisterController extends Controller
{
    public function create()
    {
        return Inertia::render('auth/RegisterPage');
    }

    public function store(StoreUserRequest $request)
    {
        $client_role = Role::where('slug', 'client')->first();
        try {
            $user = User::create([
                'full_name' => $request->full_name,
                'login' => $request->login,
                'email' => $request->email,
                'password' => $request->password,
                'role_id' => $client_role->id,
            ]);

            Auth::login($user);

            return redirect()->intended(route('profile.index'));
        } catch (Exception $e) {
            dd($e->getMessage());
            return back()->with(
                'error',
                'Произошла ошибка при регистрации. Попробуйте позже.'
            );
        }
    }
}
