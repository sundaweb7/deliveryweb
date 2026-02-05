<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PromoService;
use App\Models\Order;

class PromoApplyController extends Controller
{
    public function apply(Request $request)
    {
        $data = $request->validate(['code'=>'required','order_amount'=>'required|numeric','order_id'=>'nullable|exists:orders,id']);
        $user = $request->user();
        $service = new PromoService();
        $res = $service->validatePromoForUser($data['code'], $user, (float)$data['order_amount']);
        if (! $res['ok']) return response()->json(['message'=>$res['message']],400);

        // if order provided, optionally apply
        if (! empty($data['order_id'])) {
            $order = Order::find($data['order_id']);
            $service->applyPromoToOrder($res['promo'], $order, $user);
        }

        return response()->json(['discount'=>$res['discount'],'promo'=>['code'=>$res['promo']->code,'title'=>$res['promo']->title]]);
    }
}