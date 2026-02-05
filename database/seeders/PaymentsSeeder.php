<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Str;

class PaymentsSeeder extends Seeder
{
    public function run()
    {
        $orders = Order::limit(10)->get();
        foreach($orders as $o){
            Payment::create([
                'order_id'=>$o->id,
                'reference'=>Str::random(10),
                'method'=>'qris',
                'amount'=>$o->subtotal + $o->delivery_fee,
                'status'=> (rand(0,10) > 2) ? 'success' : 'pending',
                'raw_callback'=>json_encode(['sample'=>'data'])
            ]);
        }
    }
}