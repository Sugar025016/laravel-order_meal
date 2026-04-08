<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CartShop;
use App\Models\CartItem;
use App\Models\Product;

class CartItemSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        // 遍歷所有 CartShop
        CartShop::all()->each(function ($cartShop) use ($products) {
            // 隨機決定生成幾個商品
            $count = rand(1, 5);

            for ($i = 0; $i < $count; $i++) {
                $product = $products->random();

                CartItem::create([
                    'cart_shop_id'   => $cartShop->id,
                    'product_id'     => $product->id,
                    'qty'            => rand(1, 3),
                    'remark' => "不要辣，少油", // 生成快照價格
                ]);
            }
        });
    }
}
