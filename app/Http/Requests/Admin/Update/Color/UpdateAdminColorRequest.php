<?php

namespace App\Http\Requests\Admin\Update\Color;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminColorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:7', 'regex:/^#([A-Fa-f0-9]{6})$/'],
        ];
    }
}
