<?php

namespace App\Http\Requests\Admin\Update\Material;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminMaterialRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }
}
