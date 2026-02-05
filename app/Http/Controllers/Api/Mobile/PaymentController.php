<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function createDuitkuPayment(Request $request)
    {
        $data = $request->validate(['order_id'=>'required|exists:orders,id']);

        $res = $this->paymentService->createQrisPayment($data['order_id']);

        if (! empty($res['ok']) && $res['ok']) {
            return response()->json(['ok'=>true,'payment_id'=>$res['payment_id'],'pay_url'=>$res['pay_url']]);
        }

        return response()->json(['ok'=>false,'message'=>$res['message'] ?? 'error','details'=>$res['response'] ?? $res['error'] ?? null], 400);
    }
}
