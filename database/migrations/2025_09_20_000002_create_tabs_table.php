<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    // 主表 tab
    Schema::create('tabs', function (Blueprint $table) {
      $table->id();
      $table->string('name', 255);
      $table->boolean('is_show')->default(false);

      // shop 關聯
      $table->unsignedBigInteger('shop_id');

      $table->timestamps();

      // 外鍵約束
      $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
    });

    // 多對多中間表 tab_product
    Schema::create('tab_product', function (Blueprint $table) {
      $table->unsignedBigInteger('tab_id');
      $table->unsignedBigInteger('product_id');

      $table->unique(['tab_id', 'product_id']);

      $table->timestamps();

      // 外鍵約束
      $table->foreign('tab_id')->references('id')->on('tabs')->onDelete('cascade');
      $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('tab_product');
    Schema::dropIfExists('tabs');
  }
};
