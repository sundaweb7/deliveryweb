<?php

namespace App\Http\Requests\Api\Mobile;

use Illuminate\Foundation\Http\FormRequest;

class DriverOnlineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'is_online'=>'required|boolean',
            'lat'=>'nullable|numeric',
            'lng'=>'nullable|numeric'
        ];
    }
}
