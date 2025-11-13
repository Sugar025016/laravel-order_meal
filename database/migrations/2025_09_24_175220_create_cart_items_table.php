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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_shop_id')->constrained('cart_shops')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('qty')->default(1);
            $table->string('remark', 255)->nullable();
            // $table->decimal('price_snapshot', 10, 2)->nullable(); // 下單時快照價
            $table->timestamps();
            // $table->unique(['cart_shop_id', 'product_id']); // 同一 cart_shop 內同商品只儲存一筆（可選）
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
