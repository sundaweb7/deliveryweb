<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Avoid creating payments table if it already exists (duplicate migration guard)
        if (Schema::hasTable('payments')) {
            return;
        }

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->nullable();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('provider')->nullable();
            $table->string('reference')->nullable();
            $table->string('method')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->enum('status',['pending','paid','success','failed'])->default('pending');
            $table->text('meta')->nullable();
            $table->json('raw_callback')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};