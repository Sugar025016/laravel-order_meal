<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $data = [
            // shop_id 1：飲料、甜點
            ['shop_id' => 1, 'category_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['shop_id' => 1, 'category_id' => 3, 'created_at' => $now, 'updated_at' => $now],

            // shop_id 2：主食、小吃
            ['shop_id' => 2, 'category_id' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['shop_id' => 2, 'category_id' => 4, 'created_at' => $now, 'updated_at' => $now],

            // shop_id 3：火鍋、其他
            ['shop_id' => 3, 'category_id' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['shop_id' => 3, 'category_id' => 6, 'created_at' => $now, 'updated_at' => $now],

            // shop_id 4：飲料、主食、甜點
            ['shop_id' => 4, 'category_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['shop_id' => 4, 'category_id' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['shop_id' => 4, 'category_id' => 3, 'created_at' => $now, 'updated_at' => $now],

            // shop_id 5：小吃、飲料
            ['shop_id' => 5, 'category_id' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['shop_id' => 5, 'category_id' => 1, 'created_at' => $now, 'updated_at' => $now],

            // shop_id 6：主食、火鍋
            ['shop_id' => 6, 'category_id' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['shop_id' => 6, 'category_id' => 5, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('shop_category')->insert($data);
    }
}
