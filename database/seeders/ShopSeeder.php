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
        'address_data_id' => 345,
        'detail' => '12號5樓',
        'user_id' => 1,
      ]
    );

    Shop::updateOrCreate(
      ['brand' => '山景咖啡', 'branch' => '高雄店2'],
      [
        'phone' => '0912345678',
        'address_data_id' => 22345,
        'detail' => '12號5樓',
        'user_id' => 1,
      ]
    );

    Shop::updateOrCreate(
      ['brand' => '海洋餐廳', 'branch' => '台中店'],
      [
        'phone' => '0987654321',
        'address_data_id' => 345,
        'detail' => '5號3樓',
        'user_id' => 2,
      ]
    );

    Shop::updateOrCreate(
      ['brand' => '綠意餐廳', 'branch' => '台北店'],
      [
        'phone' => '0911223344',
        'address_data_id' => 321,
        'detail' => '8號2樓',
        'user_id' => 1,
      ]
    );

    Shop::updateOrCreate(
      ['brand' => '城市咖啡', 'branch' => '新竹店'],
      [
        'phone' => '0922334455',
        'address_data_id' => 890,
        'detail' => '10號1樓',
        'user_id' => 2,
      ]
    );

    Shop::updateOrCreate(
      ['brand' => '山水小館', 'branch' => '台南店'],
      [
        'phone' => '0933445566',
        'address_data_id' => 765,
        'detail' => '3號5樓',
        'user_id' => 2,
      ]
    );
  } //

}
