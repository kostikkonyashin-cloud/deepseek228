<?php

namespace App\Http\Requests\Admin\Update\Size;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminSizeRequest extends FormRequest
{
    public function rules(): array
    {
        $sizeId = $this->route('size')->id ?? $this->size_id;

        return [
            'name' => ['required', 'string', 'max:255', 'unique:sizes,name,' . $sizeId],
            'slug' => ['required', 'string', 'max:255', 'unique:sizes,slug,' . $sizeId],
            'size_type_id' => ['nullable', 'exists:size_types,id'],
            'is_active' => ['boolean'],
        ];
    }
}
