<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string'],
            'login' => ['required', 'string', 'unique:users,login'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Имя обязательно для заполнения',
            'login.required' => 'Логин обязателен для заполнения',
            'login.unique' => 'Этот логин уже занят',
            'email.required' => 'Email обязателен для заполнения',
            'email.email' => 'Введите корректный email',
            'email.unique' => 'Этот email уже зарегистрирован',
            'password.required' => 'Пароль обязателен для заполнения',
            'password.confirmed' => 'Пароли не совпадают',
            'password.min' => 'Пароль должен содержать минимум 8 символов',
        ];
    }
}
