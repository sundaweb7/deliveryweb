<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Mitra;
use Illuminate\Support\Facades\Http;

class MobilePaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_initiate_payment_and_receive_pay_url()
    {
        $user = User::create(['name'=>'C','email'=>'customer@example.com','password'=>bcrypt('password'),'role'=>'customer']);
        $mitraUser = User::create(['name'=>'M','email'=>'m@example.com','password'=>bcrypt('password'),'role'=>'mitra']);
        $mitra = Mitra::create(['user_id'=>$mitraUser->id,'name'=>'Mitra B','address'=>'addr','lat'=>0,'lng'=>0]);
        $order = Order::create(['order_no'=>'ORD'.time(),'customer_id'=>$user->id,'mitra_id'=>$mitra->id,'pickup_lat'=>0,'pickup_lng'=>0,'drop_lat'=>0,'drop_lng'=>0,'distance_km'=>1,'delivery_fee'=>5000,'admin_fee'=>500,'subtotal'=>10000,'status'=>'pending']);

        Http::fake(['*' => Http::response(['pay_url'=>'https://duitku.test/pay/abc123','reference'=>'REF123'],200)]);

        $this->actingAs($user)
            ->postJson('/customer/payments/duitku', ['order_id'=>$order->id])
            ->assertStatus(200)
            ->assertJsonStructure(['ok','payment_id','pay_url']);
    }
}
