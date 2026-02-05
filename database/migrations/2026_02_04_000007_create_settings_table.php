<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value')->nullable();
            $table->timestamps();
        });

        // Seed default values
        DB::table('settings')->insert([
            ['key'=>'admin_fee_percent','value'=>env('ADMIN_FEE_PERCENT', 10)],
            ['key'=>'price_per_km','value'=>env('PRICE_PER_KM', 3000)],
            ['key'=>'max_radius_km','value'=>env('MAX_RADIUS_KM', 20)],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
