<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('addresses', function (Blueprint $table) {
      $table->id(); // id 自增
      $table->string('city')->nullable();
      $table->string('area')->nullable();
      $table->string('street')->nullable();
      $table->string('detail')->nullable();
      $table->double('lat', 10, 7)->default(0); // 精度更合理
      $table->double('lng', 10, 7)->default(0); // 精度更合理

      $table->unsignedBigInteger('user_id');

      $table->timestamps(); // created_at / updated_at

      // 外鍵
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  public function down(): void
  {
    Schema::table('addresses', function (Blueprint $table) {
      $table->dropForeign(['user_id']);
    });
    Schema::dropIfExists('addresses');
  }
};
