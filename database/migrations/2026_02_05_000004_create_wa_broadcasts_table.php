<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wa_broadcasts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->enum('target', ['all','customer','mitra','driver'])->default('all');
            $table->enum('status', ['pending','sent','failed'])->default('pending');
            $table->timestamps();
        });

        Schema::create('wa_broadcast_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wa_broadcast_id')->constrained('wa_broadcasts')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('phone')->nullable();
            $table->enum('status', ['sent','failed'])->nullable();
            $table->text('response')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wa_broadcast_logs');
        Schema::dropIfExists('wa_broadcasts');
    }
};