<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shop;

class ShopSeeder extends Seeder
{
  public function run(): void
  {
    // 台南市 永康區 新民街 100號5樓
    Shop::updateOrCreate(
      ['brand' => '海洋餐廳', 'branch' => '永康店'],
      [
        'phone' => '0912345678',
        'city' => '台南市',
        'area' => '永康區',
        'street' => '新民街',
        'detail' => '100號5樓',
        'user_id' => 1,
        'image_path' => 'https://picsum.photos/300/200?random=7',
        'is_orderable' => true,
        'lat' => 23.0406434,
        'lng' => 120.2416955,
        'delivery_km' => rand(3, 10),
        'delivery_price' => rand(1, 10) * 100,
      ]
    );

    // 苗栗縣 竹南鎮 公園五街 12號5樓
    Shop::updateOrCreate(
      ['brand' => '海洋餐廳', 'branch' => '苗栗縣店'],
      [
        'phone' => '0912345678',
        'city' => '苗栗縣',
        'area' => '竹南鎮',
        'street' => '公園五街',
        'detail' => '12號5樓',
        'user_id' => 1,
        'image_path' => 'https://picsum.photos/300/200?random=8',
        'is_orderable' => true,
        'lat' => 24.6911373,
        'lng' => 120.8901748,
        'delivery_km' => rand(3, 10),
        'delivery_price' => rand(1, 10) * 100,
      ]
    );

    // 台北市 中山區 南京東路三段 12號5樓
    Shop::updateOrCreate(
      ['brand' => '山景咖啡', 'branch' => '台北店'],
      [
        'phone' => '0912345678',
        'description' => '這是山景咖啡的台北分店',
        'city' => '台北市',
        'area' => '中山區',
        'street' => '南京東路三段',
        'detail' => '12號5樓',
        'user_id' => 1,
        'image_path' => 'https://picsum.photos/300/200?random=6',
        'is_orderable' => true,
        'lat' => 25.0517631,
        'lng' => 121.5373624,
        'delivery_km' => rand(3, 10),
        'delivery_price' => rand(1, 10) * 100,
      ]
    );

    // 台北市 中山區 南京東路三段 5號3樓
    Shop::updateOrCreate(
      ['brand' => '海洋餐廳', 'branch' => '台北分店'],
      [
        'phone' => '0987654321',
        'city' => '台北市',
        'area' => '中山區',
        'street' => '南京東路三段',
        'detail' => '5號3樓',
        'user_id' => 2,
        'image_path' => 'https://picsum.photos/300/200?random=4',
        'is_orderable' => true,
        'lat' => 25.0521519,
        'lng' => 121.5383165,
        'delivery_km' => rand(3, 10),
        'delivery_price' => rand(1, 10) * 100,
      ]
    );

    // 台北市 信義區 松高路 8號2樓
    Shop::updateOrCreate(
      ['brand' => '綠意餐廳', 'branch' => '台北總店'],
      [
        'phone' => '0911223344',
        'city' => '台北市',
        'area' => '信義區',
        'street' => '松高路',
        'detail' => '8號2樓',
        'user_id' => 1,
        'image_path' => 'https://picsum.photos/300/200?random=3',
        'is_orderable' => true,
        'lat' => 25.0385456,
        'lng' => 121.5670079,
        'delivery_km' => rand(3, 10),
        'delivery_price' => rand(1, 10) * 100,
      ]
    );

    // 台北市 信義區 松高路 3號5樓
    Shop::updateOrCreate(
      ['brand' => '山水小館', 'branch' => '台北店'],
      [
        'phone' => '0933445566',
        'city' => '台北市',
        'area' => '信義區',
        'street' => '松高路',
        'detail' => '3號5樓',
        'user_id' => 2,
        'image_path' => 'https://picsum.photos/300/200?random=1',
        'is_orderable' => true,
        'lat' => 25.0395386,
        'lng' => 121.5643221,
        'delivery_km' => rand(3, 10),
        'delivery_price' => rand(1, 10) * 100,
      ]
    );

    // 台北市 信義區 松高路 B1 美食街
    Shop::updateOrCreate(
      ['brand' => '綠意餐廳', 'branch' => '信義店'],
      [
        'phone' => '0911223344',
        'city' => '台北市',
        'area' => '信義區',
        'street' => '松高路',
        'detail' => 'B1 美食街',
        'user_id' => 1,
        'image_path' => 'https://picsum.photos/300/200?random=18',
        'is_orderable' => true,
        'lat' => 25.0387489,
        'lng' => 121.5666702,
        'delivery_km' => rand(3, 10),
        'delivery_price' => rand(1, 10) * 100,
      ]
    );

    // 高雄市 鼓山區 美術館路 3樓露台區
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
        'is_orderable' => true,
        'lat' => 22.6544186,
        'lng' => 120.2935832,
        'delivery_km' => rand(3, 10),
        'delivery_price' => rand(1, 10) * 100,
      ]
    );

    // 新竹市 東區 光復路一段 近清大側門
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
        'is_orderable' => true,
        'lat' => 24.7959713,
        'lng' => 120.9975895,
        'delivery_km' => rand(3, 10),
        'delivery_price' => rand(1, 10) * 100,
      ]
    );

    // 台南市 中西區 民族路二段 轉角粉紅招牌
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
        'is_orderable' => true,
        'lat' => 22.9956797,
        'lng' => 120.2057821,
        'delivery_km' => rand(3, 10),
        'delivery_price' => rand(1, 10) * 100,
      ]
    );

    // 台中市 西屯區 河南路二段 逢甲夜市旁
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
        'is_orderable' => true,
        'lat' => 24.1758788,
        'lng' => 120.6588606,
        'delivery_km' => rand(3, 10),
        'delivery_price' => rand(1, 10) * 100,
      ]
    );

    // 桃園市 中壢區 中正路 火車站出口對面
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
        'is_orderable' => true,
        'lat' => 24.9605947,
        'lng' => 121.2093753,
        'delivery_km' => rand(3, 10),
        'delivery_price' => rand(1, 10) * 100,
      ]
    );




    // 台南市 中西區 民族路二段 轉角粉紅招牌
    Shop::updateOrCreate(
      ['brand' => 'XX甜點屋', 'branch' => '台南店'],
      [
        'phone' => '0944555666',
        'city' => '台南市',
        'area' => '中西區',
        'street' => '民族路二段',
        'detail' => '轉角粉紅招牌',
        'user_id' => 3,
        'image_path' => 'https://picsum.photos/300/200?random=11',
        'is_orderable' => true,
        'lat' => 22.9956797,
        'lng' => 120.2057821,
        'delivery_km' => rand(3, 10),
        'delivery_price' => rand(1, 10) * 100,
      ]
    );



    // 台南市 中西區 民族路二段 轉角粉紅招牌
    Shop::updateOrCreate(
      ['brand' => '幸福奶茶屋', 'branch' => '台南店'],
      [
        'phone' => '0944555666',
        'city' => '台南市',
        'area' => '中西區',
        'street' => '民族路二段',
        'detail' => '轉角粉紅招牌',
        'user_id' => 3,
        'image_path' => 'https://picsum.photos/300/200?random=11',
        'is_orderable' => true,
        'lat' => 22.9956797,
        'lng' => 120.2057821,
        'delivery_km' => rand(3, 10),
        'delivery_price' => rand(1, 10) * 100,
      ]
    );
  }
}
