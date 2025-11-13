<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // 每個 shop 產生 5~10 筆假資料
        for ($shopId = 1; $shopId <= 14; $shopId++) {
            for ($week = 1; $week <= 7; $week++) {
                $count = rand(0, 3);
                for ($i = 0; $i < $count; $i++) {
                    $startMinutes = rand(0, 47) * 30; // 0~23:30
                    $duration = rand(1, 24) * 30; // 30min ~ 12h
                    $endMinutes = $startMinutes + $duration;

                    DB::table('schedules')->insert([
                        'week' => $week,
                        'start_time' => $startMinutes,
                        'end_time' => $endMinutes,
                        'shop_id' => $shopId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
