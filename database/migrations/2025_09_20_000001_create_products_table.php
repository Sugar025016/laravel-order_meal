<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('products', function (Blueprint $table) {
      $table->id(); // id 自增
      $table->string('name', 255);
      $table->string('description', 255)->nullable();
      $table->integer('price');
      $table->boolean('is_orderable')->default(true);
      $table->string('image_path')->nullable();

      // 外鍵關聯
      $table->unsignedBigInteger('shop_id');

      $table->timestamps();
      $table->softDeletes();

      // 外鍵約束
      $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('products');
  }
};
