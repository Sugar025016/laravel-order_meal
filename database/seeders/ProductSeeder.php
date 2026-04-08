<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $products = [];

        // 每家店的隨機商品
        for ($shopId = 1; $shopId <= 14; $shopId++) {
            // 隨機商品
            for ($i = 1; $i <= 3; $i++) {
                $products[] = [
                    'name' => "商品{$i} - 店{$shopId}",
                    'description' => "這是商品{$i}的描述",
                    'price' => rand(50, 500),
                    'is_orderable' => (bool)rand(0, 1),
                    'image_path' => 'https://picsum.photos/300/200?random=' . rand(12, 50),
                    'shop_id' => $shopId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // 固定品名商品
            $fixedProducts = [
                '可樂',
                '漢堡',
                '薯條',
                '珍珠奶茶',
                '蛋糕'
            ];

            foreach ($fixedProducts as $name) {
                $products[] = [
                    'name' => $name,
                    'description' => "美味的{$name}",
                    'price' => rand(50, 500),
                    'is_orderable' => true,
                    'image_path' => 'https://picsum.photos/300/200?random=' . rand(12, 50),
                    'shop_id' => $shopId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('products')->insert($products);
    }
}
