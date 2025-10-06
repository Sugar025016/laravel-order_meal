<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('schedules', function (Blueprint $table) {
      $table->id();
      $table->integer('week'); // 星期數字
      $table->time('start_time'); // LocalTime 對應 time
      $table->time('end_time');
      $table->integer('type')->nullable(); // 可空
      $table->unsignedBigInteger('shop_id'); // 多對一關聯
      $table->timestamps();

      // 外鍵約束
      $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('schedules');
  }
};
