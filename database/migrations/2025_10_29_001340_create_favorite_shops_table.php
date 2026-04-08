<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('favorite_shops', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('user_id');
            // $table->unsignedBigInteger('shop_id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 使用者
            $table->foreignId('shop_id')->constrained()->onDelete('cascade'); // 商店
            $table->timestamps();

            $table->unique(['user_id', 'shop_id']); // 防止重複喜愛

            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite_shops');
    }
};
