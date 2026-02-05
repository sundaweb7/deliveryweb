<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Other seeders
        $this->call([
            \Database\Seeders\WalletsSeeder::class,
            \Database\Seeders\PaymentsSeeder::class,
            \Database\Seeders\DriverLocationsSeeder::class,
            \Database\Seeders\OrderTimelinesSeeder::class,
            \Database\Seeders\PromosSeeder::class,
            \Database\Seeders\BannersSeeder::class,
            \Database\Seeders\CategoriesSeeder::class,
        ]);
    }
}
