<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id'); // 下單人
            $table->unsignedBigInteger('shop_id'); // 店家

            $table->decimal('total_price', 10, 2)->default(0); // 總金額
            $table->string('status', 50)->default('pending'); // pending / paid / delivered / cancelled
            $table->string('payment_method', 50)->nullable(); // cash / credit / linepay 等
            $table->text('remark')->nullable(); // 備註
            $table->timestamp('take_time')->nullable();

            // 地址快照
            $table->string('city')->nullable();
            $table->string('area')->nullable();
            $table->string('street')->nullable();
            $table->string('detail')->nullable();
            $table->double('lat', 10, 7)->default(0); // 精度更合理
            $table->double('lng', 10, 7)->default(0); // 精度更合理

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
