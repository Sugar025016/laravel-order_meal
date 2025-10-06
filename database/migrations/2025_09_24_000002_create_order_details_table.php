<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('order_details', function (Blueprint $table) {
      $table->id();

      $table->unsignedBigInteger('order_id'); // 對應 orders
      $table->unsignedBigInteger('product_id'); // 對應產品
      // $table->unsignedBigInteger('sku_id')->nullable(); // 如果有 SKU

      $table->string('product_name', 255); // 冗餘存快照
      $table->decimal('product_price', 10, 2); // 當時價格
      $table->integer('qty')->default(1);
      $table->decimal('total_price', 10, 2); // 單價 * 數量
      $table->text('remark')->nullable(); // 備註

      $table->timestamps();

      $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
      $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('order_details');
  }
};
