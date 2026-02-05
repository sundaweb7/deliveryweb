<?php

namespace App\Http\Controllers\Api\Admin;

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

    public function index(Request $request)
    {
        $q = Payment::with('order')->orderBy('created_at','desc');
        return response()->json($q->paginate(20));
    }

    public function show($id)
    {
        $payment = Payment::with('order')->findOrFail($id);
        return response()->json($payment);
    }

    public function createDuitkuPayment(Request $request)
    {
        $data = $request->validate([
            'order_id'=>'required|exists:orders,id'
        ]);

        $res = $this->paymentService->createQrisPayment($data['order_id']);
        return response()->json($res);
    }
}
