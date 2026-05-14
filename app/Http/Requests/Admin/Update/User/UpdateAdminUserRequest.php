<?php

namespace App\Http\Requests\Admin\Update\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminUserRequest extends FormRequest
{
    public function rules(): array
    {
        $userId = $this->route('user')->id ?? $this->user_id;

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'login' => ['required', 'string', 'max:255', 'unique:users,login,' . $userId],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $userId],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
        ];
    }
}
