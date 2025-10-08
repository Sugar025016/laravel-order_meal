<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shop;

class ShopSeeder extends Seeder
{
  /**
   * 執行資料填充。
   */
  public function run(): void
  {
    Shop::updateOrCreate(
      ['brand' => '海洋餐廳1', 'branch' => '台中店1'],
      [
        'phone' => '0912345678',
        'address_data_id' => 12345,
        'detail' => '12號5樓',
        'user_id' => 1,
      ]
    );

    // 你可以再加其他假資料
    Shop::updateOrCreate(
      ['brand' => '山景咖啡', 'branch' => '高雄店'],
      [
        'phone' => '0912345678',
        'address_data_id' => 22345,
        'detail' => '12號5樓',
        'user_id' => 1,
      ]
    );
  } //

}
