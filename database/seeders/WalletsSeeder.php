<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Services\WalletService;

class WalletsSeeder extends Seeder
{
    public function run()
    {
        $service = new WalletService();
        User::chunk(100, function($users) use ($service){
            foreach($users as $u){
                $service->ensureWalletForOwner($u);
            }
        });
    }
}