<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannersSeeder extends Seeder
{
    public function run()
    {
        Banner::create(['title'=>'Promo Hari Ini','image'=>null,'link'=>'#','status'=>'active']);
        Banner::create(['title'=>'Gratis Ongkir !','image'=>null,'link'=>'#','status'=>'active']);
    }
}