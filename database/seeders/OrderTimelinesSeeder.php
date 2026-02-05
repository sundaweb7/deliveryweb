<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderTimeline;

class OrderTimelinesSeeder extends Seeder
{
    public function run()
    {
        $orders = Order::limit(20)->get();
        foreach($orders as $o){
            OrderTimeline::create(['order_id'=>$o->id,'status'=>$o->status,'note'=>'Seeded timeline entry']);
        }
    }
}