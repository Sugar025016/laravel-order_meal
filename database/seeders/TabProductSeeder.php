<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TabProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tab_product')->truncate(); // 清空舊資料
        $now = now();
        $tabProducts = [];

        // 取出所有 tab 和 product
        // $tabs = DB::table('tabs')->pluck('id', 'shop_id'); // shop_id => tab_id
        $tabs = DB::table('tabs')->get(['id', 'shop_id']);
        $products = DB::table('products')->get(['id', 'shop_id']);

        // 為每個 tab 分配幾個商品（同店商品）
        // foreach ($tabs as $shopId => $tabId) {
        //     // 找出屬於該商店的商品
        //     $shopProducts = $products->where('shop_id', $shopId)->pluck('id')->toArray();

        //     // 隨機取 2~4 個商品
        //     $selectedProducts = collect($shopProducts)->random(min(count($shopProducts), rand(2, 4)));

        //     foreach ($selectedProducts as $productId) {
        //         $tabProducts[] = [
        //             'tab_id' => $tabId,
        //             'product_id' => $productId,
        //             'created_at' => $now,
        //             'updated_at' => $now,
        //         ];
        //     }
        // }
        foreach ($tabs as $tab) {
            // 找出屬於該商店的商品
            $shopProducts = $products->where('shop_id', $tab->shop_id)->pluck('id')->toArray();

            if (empty($shopProducts)) {
                continue;
            }

            // 隨機取 2~4 個商品
            $selectedProducts = collect($shopProducts)->random(min(count($shopProducts), rand(2, 4)));

            foreach ($selectedProducts as $productId) {
                $tabProducts[] = [
                    'tab_id' => $tab->id,
                    'product_id' => $productId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // 寫入資料庫
        DB::table('tab_product')->insert($tabProducts);
    }
}
