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
      ['brand' => '海洋餐廳', 'branch' => '苗栗縣'],
      [
        'phone' => '0912345678',

        "city" => "苗栗縣",
        "area" =>  "竹南鎮",
        "street" => "公園五街",
        'detail' => '12號5樓',
        'user_id' => 1,
        'image_path' => 'https://picsum.photos/300/200?random=7',
        'is_orderable' => true,
      ]
    );

    // 你可以再加其他假資料
    Shop::updateOrCreate(
      ['brand' => '山景咖啡', 'branch' => '高雄店'],
      [
        'phone' => '0912345678',
        'description' => '這是山景咖啡的高雄分店',
        'city' => '台北市',
        'area' => '中山區',
        'street' => '南京東路三段',
        'detail' => '12號5樓',
        'user_id' => 1,
        'image_path' => 'https://picsum.photos/300/200?random=6',
        'is_orderable' => true,
      ]
    );

    Shop::updateOrCreate(
      ['brand' => '山景咖啡', 'branch' => '高雄店2'],
      [
        'phone' => '0912345678',
        'description' => '這是山景咖啡的高雄分店',
        'city' => '台北市',
        'area' => '中山區',
        'street' => '南京東路三段',
        'detail' => '12號5樓',
        'user_id' => 1,
        'image_path' => 'https://picsum.photos/300/200?random=5',
        'is_orderable' => true,
      ]
    );

    Shop::updateOrCreate(
      ['brand' => '海洋餐廳', 'branch' => '台中店'],
      [
        'phone' => '0987654321',
        'description' => '這是山景咖啡的高雄分店',
        'city' => '台北市',
        'area' => '中山區',
        'street' => '南京東路三段',
        'detail' => '5號3樓',
        'user_id' => 2,
        'image_path' => 'https://picsum.photos/300/200?random=4',
        'is_orderable' => true,
      ]
    );

    Shop::updateOrCreate(
      ['brand' => '綠意餐廳', 'branch' => '台北總店'],
      [
        'phone' => '0911223344',
        'description' => '這是山景咖啡的高雄分店',
        'city' => '台北市',
        'area' => '信義區',
        'street' => '松高路',
        'detail' => '8號2樓',
        'user_id' => 1,
        'image_path' => 'https://picsum.photos/300/200?random=3',
        'is_orderable' => true,
      ]
    );

    Shop::updateOrCreate(
      ['brand' => '城市咖啡', 'branch' => '新竹店'],
      [
        'phone' => '0922334455',
        'description' => '這是山景咖啡的高雄分店',
        'city' => '台北市',
        'area' => '信義區',
        'street' => '松高路',
        'detail' => '10號1樓',
        'user_id' => 2,
        'image_path' => 'https://picsum.photos/300/200?random=2',
      ]
    );

    Shop::updateOrCreate(
      ['brand' => '山水小館', 'branch' => '台南店'],
      [
        'phone' => '0933445566',

        'city' => '台北市',
        'area' => '信義區',
        'street' => '松高路',
        'detail' => '3號5樓',
        'user_id' => 2,
        'image_path' => 'https://picsum.photos/300/200?random=1',
      ]
    );


    Shop::updateOrCreate(
      ['brand' => '海洋餐廳', 'branch' => '台中店1'],
      [
        'phone' => '0912345678',

        'city' => '苗栗縣',
        'area' => '竹南鎮',
        'street' => '公園五街',
        'detail' => '12號5樓',
        'user_id' => 1,
        'image_path' => 'https://picsum.photos/300/200?random=17',
      ]
    );

    Shop::updateOrCreate(
      ['brand' => '綠意餐廳', 'branch' => '台北店'],
      [
        'phone' => '0911223344',

        'city' => '台北市',
        'area' => '信義區',
        'street' => '松高路',
        'detail' => 'B1 美食街',
        'user_id' => 1,
        'image_path' => 'https://picsum.photos/300/200?random=18',
      ]
    );

    Shop::updateOrCreate(
      ['brand' => '山景咖啡', 'branch' => '高雄店'],
      [
        'phone' => '0922333444',

        'city' => '高雄市',
        'area' => '鼓山區',
        'street' => '美術館路',
        'detail' => '3樓露台區',
        'user_id' => 2,
        'image_path' => 'https://picsum.photos/300/200?random=19',
      ]
    );

    Shop::updateOrCreate(
      ['brand' => '和風便當', 'branch' => '新竹店'],
      [
        'phone' => '0933444555',

        'city' => '新竹市',
        'area' => '東區',
        'street' => '光復路一段',
        'detail' => '近清大側門',
        'user_id' => 2,
        'image_path' => 'https://picsum.photos/300/200?random=10',
      ]
    );

    Shop::updateOrCreate(
      ['brand' => '幸福甜點屋', 'branch' => '台南店'],
      [
        'phone' => '0944555666',

        'city' => '台南市',
        'area' => '中西區',
        'street' => '民族路二段',
        'detail' => '轉角粉紅招牌',
        'user_id' => 3,
        'image_path' => 'https://picsum.photos/300/200?random=11',
      ]
    );

    Shop::updateOrCreate(
      ['brand' => '火鍋食堂', 'branch' => '台中店'],
      [
        'phone' => '0955666777',

        'city' => '台中市',
        'area' => '西屯區',
        'street' => '河南路二段',
        'detail' => '逢甲夜市旁',
        'user_id' => 3,
        'image_path' => 'https://picsum.photos/300/200?random=12',
      ]
    );

    Shop::updateOrCreate(
      ['brand' => '港味燒臘', 'branch' => '桃園店'],
      [
        'phone' => '0966777888',

        'city' => '桃園市',
        'area' => '中壢區',
        'street' => '中正路',
        'detail' => '火車站出口對面',
        'user_id' => 4,
        'image_path' => 'https://picsum.photos/300/200?random=13',
      ]
    );

    Shop::updateOrCreate(
      ['brand' => '日香壽司', 'branch' => '台北店'],
      [
        'phone' => '0977888999',

        'city' => '台北市',
        'area' => '中山區',
        'street' => '南京東路三段',
        'detail' => '大樓一樓角間',
        'user_id' => 4,
        'image_path' => 'https://picsum.photos/300/200?random=14',
      ]
    );

    Shop::updateOrCreate(
      ['brand' => '早安早餐', 'branch' => '新北店'],
      [
        'phone' => '0988999000',

        'city' => '新北市',
        'area' => '板橋區',
        'street' => '文化路一段',
        'detail' => '捷運出口旁',
        'user_id' => 5,
        'image_path' => 'https://picsum.photos/300/200?random=15',
      ]
    );

    Shop::updateOrCreate(
      ['brand' => '夜市小吃王', 'branch' => '嘉義店'],
      [
        'phone' => '0999000111',

        'city' => '嘉義市',
        'area' => '東區',
        'street' => '中山路',
        'detail' => '夜市口第一攤',
        'user_id' => 3,
        'image_path' => 'https://picsum.photos/300/200?random=16',
      ]
    );
  } //

}
