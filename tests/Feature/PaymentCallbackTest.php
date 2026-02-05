<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Mitra;

class PaymentCallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_callback_valid_signature_marks_order_paid_and_updates_payment()
    {
        $user = User::create(['name'=>'C','email'=>'c2@example.com','password'=>bcrypt('password'),'role'=>'customer']);
        $mitraUser = User::create(['name'=>'M','email'=>'m2@example.com','password'=>bcrypt('password'),'role'=>'mitra']);
        $mitra = Mitra::create(['user_id'=>$mitraUser->id,'name'=>'Mitra C','address'=>'addr','lat'=>0,'lng'=>0]);

        $order = Order::create(['order_no'=>'ORD'.time(),'customer_id'=>$user->id,'mitra_id'=>$mitra->id,'pickup_lat'=>0,'pickup_lng'=>0,'drop_lat'=>0,'drop_lng'=>0,'distance_km'=>1,'delivery_fee'=>5000,'admin_fee'=>500,'subtotal'=>10000,'status'=>'pending']);

        // Set merchant key in settings
        \App\Models\Setting::setValue('duitku_merchant_key','testkey123');

        $payload = ['order_no'=>$order->order_no,'status'=>'paid','payment_id'=>'PAY123'];
        $sig = hash_hmac('sha256', json_encode($payload), 'testkey123');

        $this->postJson('/payment/duitku/callback', $payload, ['X-Duitku-Signature'=>$sig])
            ->assertStatus(200)
            ->assertJson(['message'=>'ok']);

        $this->assertDatabaseHas('payments', ['provider'=>'duitku','payment_id'=>'PAY123']);
        $this->assertDatabaseHas('orders', ['id'=>$order->id, 'status'=>'paid']);
    }
}
