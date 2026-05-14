<?php

namespace App\Http\Requests\Management\Update\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusByManagerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:processing,ready,issued,cancelled,completed'],
        ];
    }
}
