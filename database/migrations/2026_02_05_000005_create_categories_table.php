<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
            $table->timestamps();
        });

        Schema::table('products', function(Blueprint $table){
            $table->foreignId('category_id')->nullable()->after('mitra_id')->constrained('categories')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('products', function(Blueprint $table){
            $table->dropConstrainedForeignId('category_id');
        });
        Schema::dropIfExists('categories');
    }
};