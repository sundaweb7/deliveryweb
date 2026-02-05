<?php

namespace App\Http\Requests\Api\Mobile;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mitra_id'=>'required|exists:mitras,id',
            'items'=>'required|array|min:1',
            'items.*.product_id'=>'required|exists:products,id',
            'items.*.qty'=>'required|integer|min:1',
            'pickup_lat'=>'required|numeric',
            'pickup_lng'=>'required|numeric',
            'drop_lat'=>'required|numeric',
            'drop_lng'=>'required|numeric',
            'promo_code'=>'nullable|string'
        ];
    }
}
