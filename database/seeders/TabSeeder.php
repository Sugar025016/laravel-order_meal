<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TabSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // 每個 shop_id (1~14) 建立數個 tab
        foreach (range(1, 14) as $shopId) {
            $tabCount = rand(2, 5); // 每個商店 2~5 個 tab
            foreach (range(1, $tabCount) as $i) {
                DB::table('tabs')->insert([
                    'name' => $faker->words(rand(1, 3), true), // 產生 1~3 個單字組成的名稱
                    'is_show' => $faker->boolean(70), // 70% 機率為 true
                    'shop_id' => $shopId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
