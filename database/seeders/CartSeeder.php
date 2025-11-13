<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = User::pluck('id')->toArray();
        $shops = Shop::with('products:id,shop_id')->get();

        if ($userIds === [] || $shops->isEmpty()) {
            $this->command->warn('⚠️ 無法建立購物車資料，users 或 shops 無資料。');
            return;
        }

        $count = 0;

        foreach ($shops as $shop) {
            // 該店的商品 id
            $productIds = $shop->products->pluck('id')->toArray();

            if (empty($productIds)) {
                $this->command->warn("⚠️ 店舖 {$shop->id} 沒有商品，跳過。");
                continue;
            }

            // 每家店隨機生成 2~5 筆購物車項目
            $num = rand(2, 5);
            for ($i = 0; $i < $num; $i++) {
                DB::table('carts')->insert([
                    'user_id' => fake()->randomElement($userIds),
                    'shop_id' => $shop->id,
                    'product_id' => fake()->randomElement($productIds),
                    'qty' => rand(1, 5),
                    'remark' => fake()->optional()->sentence(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $count++;
            }
        }

        $this->command->info("✅ CartSeeder 建立完成，共 {$count} 筆資料。");
    }
}
