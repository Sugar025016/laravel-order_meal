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
            $count = rand(0, 21);
            $MaxEndMinutes = 0;
            $week = 0;
            $firstStartTime = 0;
            for ($i = 0; $i < $count; $i++) {
                $startMinutes = $MaxEndMinutes + rand(0, 24) * 30; // 0~23:30

                if ($startMinutes >= 10080) break;
                if ($i == 0) {
                    $firstStartTime = $startMinutes;
                }
                $duration = rand(1, 24) * 30; // 30min ~ 12h
                $endMinutes = $startMinutes + $duration;
                $MaxEndMinutes = $endMinutes;
                $week = intdiv($startMinutes, 1440) + 1;
                if ($endMinutes > 10080) {
                    $endMinutes = 10080;
                    if ($MaxEndMinutes % 1440 < $firstStartTime) {
                        DB::table('schedules')->insert([
                            'week' => 1,
                            'start_time' => 0,
                            'end_time' => $MaxEndMinutes % 1440,
                            'shop_id' => $shopId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

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
