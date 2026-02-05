<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mitra;
use App\Models\Driver;
use App\Models\Product;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Customer
        $customer = User::firstOrCreate(['email'=>'customer@example.com'],['name'=>'Customer','phone'=>'081100000','password'=>Hash::make('password'),'role'=>'customer']);
        $customer->wallet()->firstOrCreate([]);

        // Mitra user
        $mitraUser = User::firstOrCreate(['email'=>'mitra@example.com'],['name'=>'Mitra','phone'=>'081200000','password'=>Hash::make('password'),'role'=>'mitra']);
        $mitra = Mitra::firstOrCreate(['user_id'=>$mitraUser->id],['name'=>'Mitra A','address'=>'Jalan Test','lat'=>-6.200000,'lng'=>106.816666,'is_active'=>true]);
        $mitraUser->wallet()->firstOrCreate([]);
        Product::firstOrCreate(['mitra_id'=>$mitra->id,'name'=>'Nasi Goreng'],['price'=>25000,'stock'=>100]);

        // Driver user
        $driverUser = User::firstOrCreate(['email'=>'driver@example.com'],['name'=>'Driver','phone'=>'081300000','password'=>Hash::make('password'),'role'=>'driver']);
        $driver = Driver::firstOrCreate(['user_id'=>$driverUser->id],['vehicle_type'=>'motor','vehicle_number'=>'B1234XYZ','is_active'=>true,'lat'=>-6.2010,'lng'=>106.8168]);
        $driverUser->wallet()->firstOrCreate([]);
    }
}
