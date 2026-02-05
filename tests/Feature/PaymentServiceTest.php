<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use App\Models\Mitra;
use App\Models\Product;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Http;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_qris_payment_calls_duitku_and_creates_record()
    {
        $user = User::create(['name'=>'C','email'=>'c@example.com','password'=>bcrypt('password'),'role'=>'customer']);
        $mitraUser = User::create(['name'=>'M','email'=>'m@example.com','password'=>bcrypt('password'),'role'=>'mitra']);
        $mitra = Mitra::create(['user_id'=>$mitraUser->id,'name'=>'Mitra A','address'=>'addr','lat'=>0,'lng'=>0]);

        $order = Order::create(['order_no'=>'ORD'.time(),'customer_id'=>$user->id,'mitra_id'=>$mitra->id,'pickup_lat'=>0,'pickup_lng'=>0,'drop_lat'=>0,'drop_lng'=>0,'distance_km'=>1,'delivery_fee'=>5000,'admin_fee'=>500,'subtotal'=>10000,'status'=>'pending']);

        // Fake HTTP response from Duitku
        Http::fake([
            '*' => Http::response(['pay_url'=>'https://duitku.test/pay/abc123','reference'=>'REF123','data'=>['pay_url'=>'https://duitku.test/pay/abc123']], 200)
        ]);

        $svc = app(PaymentService::class);
        $res = $svc->createQrisPayment($order->id);

        $this->assertTrue(!empty($res['ok']) && $res['ok']);
        $this->assertArrayHasKey('pay_url', $res);
        $this->assertStringContainsString('duitku.test', $res['pay_url']);
    }
}
