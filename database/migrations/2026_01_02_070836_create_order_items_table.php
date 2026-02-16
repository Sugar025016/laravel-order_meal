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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');
            $table->integer('product_price'); // 單價
            $table->integer('quantity')->default(1); // 數量
            $table->integer('missing_qty')->default(0)->comment('缺貨數量'); // 數量
            $table->integer('damaged_qty')->default(0)->comment('損壞數量');
            $table->integer('subtotal')->comment('原始小計 = 單價 * 數量'); // 小計 = product_price * quantity
            $table->integer('refund_amount')->default(0)->comment('退款金額');
            $table->text('customer_note')->nullable()->comment('客戶下單時的餐點備註');
            $table->text('staff_note')->nullable()->comment('店家或客服處理備註');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            // 如果需要保留商品歷史資料，不建議建立 product_id 外鍵
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
