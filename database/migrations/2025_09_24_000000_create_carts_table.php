<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('carts', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_id');
      $table->unsignedBigInteger('shop_id');
      $table->unsignedBigInteger('product_id');
      // $table->unsignedBigInteger('sku_id')->nullable();//連接庫存，目前沒寫
      // $table->string('product_name', 255);
      // $table->decimal('price', 10, 2);
      $table->integer('qty')->default(1);
      $table->string('remark', 255)->nullable();
      $table->timestamps();

      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
      $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
      // $table->foreign('sku_id')->references('id')->on('product_skus')->onDelete('cascade');//連接庫存，目前沒寫

      $table->unique(['user_id', 'product_id']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('carts');
  }
};
