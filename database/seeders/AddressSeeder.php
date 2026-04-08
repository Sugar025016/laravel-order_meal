<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Address;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $addresses = [
            [
                "city" => "台南市",
                "area" => "永康區",
                "street" => "新民街",
                "detail" => "100號5樓",
                "lat" => 23.0406434,
                "lng" => 120.2416955,
                "user_id" => 1,
            ],
            [
                "city" => "苗栗縣",
                "area" => "竹南鎮",
                "street" => "公園五街",
                "detail" => "12號5樓",
                "lat" => 24.6911373,
                "lng" => 120.8901748,
                "user_id" => 1,
            ],
            [
                "city" => "台北市",
                "area" => "中山區",
                "street" => "南京東路三段",
                "detail" => "12號5樓",
                "lat" => 25.0517631,
                "lng" => 121.5373624,
                "user_id" => 1,
            ],
            [
                "city" => "台北市",
                "area" => "中山區",
                "street" => "南京東路三段",
                "detail" => "5號3樓",
                "lat" => 25.0521519,
                "lng" => 121.5383165,
                "user_id" => 1,
            ],
            [
                "city" => "台北市",
                "area" => "信義區",
                "street" => "松高路",
                "detail" => "8號2樓",
                "lat" => 25.0385456,
                "lng" => 121.5670079,
                "user_id" => 1,
            ],
            [
                "city" => "台北市",
                "area" => "信義區",
                "street" => "松高路",
                "detail" => "3號5樓",
                "lat" => 25.0395386,
                "lng" => 121.5643221,
                "user_id" => 1,
            ],
            [
                "city" => "苗栗縣",
                "area" => "竹南鎮",
                "street" => "公園五街",
                "detail" => "12號5樓",
                "lat" => 24.6911373,
                "lng" => 120.8901748,
                "user_id" => 1,
            ],
            [
                "city" => "台北市",
                "area" => "信義區",
                "street" => "松高路",
                "detail" => "B1 美食街",
                "lat" => 25.0387489,
                "lng" => 121.5666702,
                "user_id" => 1,
            ],
            [
                "city" => "高雄市",
                "area" => "鼓山區",
                "street" => "美術館路",
                "detail" => "3樓露台區",
                "lat" => 22.6544186,
                "lng" => 120.2935832,
                "user_id" => 1,
            ],
            [
                "city" => "新竹市",
                "area" => "東區",
                "street" => "光復路一段",
                "detail" => "近清大側門",
                "lat" => 24.7959713,
                "lng" => 120.9975895,
                "user_id" => 1,
            ],
            [
                "city" => "台南市",
                "area" => "中西區",
                "street" => "民族路二段",
                "detail" => "轉角粉紅招牌",
                "lat" => 22.9956797,
                "lng" => 120.2057821,
                "user_id" => 1,
            ],
            [
                "city" => "台中市",
                "area" => "西屯區",
                "street" => "河南路二段",
                "detail" => "逢甲夜市旁",
                "lat" => 24.1758788,
                "lng" => 120.6588606,
                "user_id" => 1,
            ],
            [
                "city" => "桃園市",
                "area" => "中壢區",
                "street" => "中正路",
                "detail" => "火車站出口對面",
                "lat" => 24.9605947,
                "lng" => 121.2093753,
                "user_id" => 1,
            ],
            [
                "city" => "台北市",
                "area" => "中山區",
                "street" => "南京東路三段",
                "detail" => "大樓一樓角間",
                "lat" => 25.0520040,
                "lng" => 121.5404370,
                "user_id" => 1,
            ],
            [
                "city" => "新北市",
                "area" => "板橋區",
                "street" => "文化路一段",
                "detail" => "捷運出口旁",
                "lat" => 25.0147970,
                "lng" => 121.4620510,
                "user_id" => 1,
            ],
            [
                "city" => "嘉義市",
                "area" => "東區",
                "street" => "中山路",
                "detail" => "夜市口第一攤",
                "lat" => 23.4818487,
                "lng" => 120.4561861,
                "user_id" => 1,
            ],
        ];

        foreach ($addresses as $a) {
            Address::create($a);
        }
    }
}
