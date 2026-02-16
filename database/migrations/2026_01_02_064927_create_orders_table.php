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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();

            $table->tinyInteger('order_type')->default(1)->comment('1=即時,2=預訂');
            $table->tinyInteger('delivery_type')->default(1)->comment('1=外送,2=自取');
            $table->tinyInteger('status')->default(1)->comment('訂單狀態');
            // 1=待確認, 2=店家已接單, 3=製作中, 4=配送中, 5=已完成, 6=取消, 7=問題訂單

            $table->string('city')->nullable();
            $table->string('area')->nullable();
            $table->string('street')->nullable();
            $table->string('detail')->nullable();
            $table->double('lat', 10, 7)->nullable();
            $table->double('lng', 10, 7)->nullable();

            $table->tinyInteger('pay_method')->default(1)->comment('1=現金,2=信用卡,3=LINE Pay');
            $table->boolean('is_cutlery')->default(false); //是否需要餐具

            $table->text('customer_note')->nullable()->comment('客戶下單備註');
            $table->text('staff_note')->nullable()->comment('客服 / 店家處理備註');

            $table->integer('subtotal')->default(0); // 商品總額，不含折扣/運費
            $table->integer('delivery_fee')->default(0);
            $table->integer('total_price')->default(0); // 實付金額
            $table->integer('refund_amount')->default(0)->comment('總退款金額');

            $table->timestamp('scheduled_time')->nullable(); // 預訂單指定時間
            $table->timestamp('estimated_delivery_time')->nullable(); // 系統估算送達時間

            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('shop_id')->references('id')->on('shops')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
