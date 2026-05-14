<?php

namespace App\Http\Requests\Admin\Store\Color;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminColorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:colors,name'],
            'code' => ['required', 'string', 'max:7', 'regex:/^#([A-Fa-f0-9]{6})$/'],
        ];
    }
}
