<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CartShop;
use App\Models\User;
use App\Models\Shop;

class CartShopSeeder extends Seeder
{
    public function run(): void
    {
        // 取得前 5 個使用者與前 5 個商店
        $users = User::take(5)->get();
        $shops = Shop::take(5)->get();

        foreach ($users as $user) {
            foreach ($shops as $shop) {
                // 隨機決定是否建立該使用者的購物車商店
                if (rand(0, 1)) {
                    CartShop::create([
                        'user_id' => $user->id,
                        'shop_id' => $shop->id,
                    ]);
                }
            }
        }
    }
}
