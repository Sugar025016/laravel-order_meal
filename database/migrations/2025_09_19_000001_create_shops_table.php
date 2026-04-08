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
    Schema::create('shops', function (Blueprint $table) {
      $table->id(); // id 自動增長
      $table->string('brand');
      $table->string('branch');
      $table->unique(['brand', 'branch']);
      $table->string('phone', 11);
      $table->string('description', 512)->nullable();
      $table->boolean('is_orderable')->default(false);
      $table->boolean('is_open')->default(false);
      $table->double('delivery_km')->default(0);
      $table->integer('delivery_price')->default(0);
      $table->timestamp('phone_verified_at')->nullable();

      $table->string('city')->nullable();
      $table->string('area')->nullable();
      $table->string('street')->nullable();
      $table->string('detail')->nullable();
      $table->double('lat', 10, 7)->default(0); // 精度更合理
      $table->double('lng', 10, 7)->default(0); // 精度更合理

      // 外鍵關聯
      $table->string('image_path')->nullable();
      $table->unsignedBigInteger('user_id');

      // Laravel 自動時間戳
      $table->timestamps();
      $table->softDeletes();


      // 外鍵約束
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });

    // category 與 shop 多對多中間表
    Schema::create('shop_category', function (Blueprint $table) {
      $table->unsignedBigInteger('shop_id');
      $table->unsignedBigInteger('category_id');

      $table->unique(['shop_id', 'category_id']);

      $table->timestamps();

      $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
      $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('shop_category');
    Schema::dropIfExists('shops');
  }
};
