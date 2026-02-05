<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // guard: if tables already exist (from previous migrations), skip
        if (! Schema::hasTable('wallets')) {
            Schema::create('wallets', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('owner_id');
                $table->string('owner_type');
                $table->decimal('balance', 14, 2)->default(0);
                $table->timestamps();
                $table->index(['owner_id','owner_type']);
            });
        }

        if (! Schema::hasTable('wallet_histories')) {
            Schema::create('wallet_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wallet_id')->constrained('wallets')->onDelete('cascade');
                $table->enum('type',['credit','debit']);
                $table->decimal('amount', 14, 2);
                $table->string('description')->nullable();
                $table->string('reference')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('wallet_histories');
        Schema::dropIfExists('wallets');
    }
};