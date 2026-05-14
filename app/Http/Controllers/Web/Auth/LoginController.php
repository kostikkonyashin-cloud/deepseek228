<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\SocialiteProvider;
use Exception;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function create()
    {
        $social_providers = SocialiteProvider::get();
        return Inertia::render('auth/LoginPage', compact(['social_providers']));
    }

    public function store(LoginRequest $request)
    {
        try {
            if (Auth::attempt($request->validated())) {
                $request->session()->regenerate();

                return redirect()->intended('/');
            }

            return back()->with(
                'error',
                'Неверные учетные данные'
            );

        } catch (Exception $e) {
            return back()->with(
                'error',
                'Произошла ошибка при входе. Попробуйте позже.'
            );
        }
    }
}
