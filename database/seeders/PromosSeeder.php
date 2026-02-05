<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promo;
use Illuminate\Support\Str;

class PromosSeeder extends Seeder
{
    public function run()
    {
        Promo::create([
            'code' => 'WELCOME10',
            'title' => 'Diskon Selamat Datang 10%',
            'description' => 'Diskon 10% untuk pembelian pertama',
            'type' => 'percent',
            'value' => 10,
            'min_order' => 0,
            'max_discount' => 50000,
            'start_date' => now()->subDay(),
            'end_date' => now()->addMonth(),
            'usage_limit' => 100,
            'used_count' => 0,
            'status' => 'active'
        ]);

        Promo::create([
            'code' => 'FLAT5000',
            'title' => 'Potongan Rp5.000',
            'description' => 'Potongan tetap 5.000 untuk semua order',
            'type' => 'fixed',
            'value' => 5000,
            'min_order' => 20000,
            'max_discount' => null,
            'start_date' => now()->subDay(),
            'end_date' => now()->addMonth(),
            'usage_limit' => 1000,
            'used_count' => 0,
            'status' => 'active'
        ]);
    }
}