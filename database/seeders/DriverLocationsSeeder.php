<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;
use App\Models\DriverLocation;

class DriverLocationsSeeder extends Seeder
{
    public function run()
    {
        $drivers = Driver::limit(10)->get();
        foreach($drivers as $d){
            DriverLocation::create(['driver_id'=>$d->id,'lat'=>-6.2+rand(-100,100)/1000,'lng'=>106.8+rand(-100,100)/1000]);
        }
    }
}