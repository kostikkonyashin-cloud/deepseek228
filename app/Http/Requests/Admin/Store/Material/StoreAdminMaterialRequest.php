<?php

namespace App\Http\Requests\Admin\Store\Material;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminMaterialRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:materials,name'],
            'is_active' => ['boolean'],
        ];
    }
}
