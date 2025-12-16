<?php

namespace App\Services;

use App\Models\Schedule;
use Illuminate\Support\Facades\DB;

class ScheduleService
{
    /**
     * 新增排程（自動處理跨夜拆分）
     */
    public function createSchedule(array $data)
    {
        $week = $data['week'];
        $start = $data['start_time']; // 分鐘
        $end = $data['end_time'];     // 分鐘
        $shopId = $data['shop_id'];

        DB::transaction(function () use ($week, $start, $end, $shopId) {

            // 如果是跨夜（例：23:00 → 02:00）
            if ($end < $start) {

                // 第一段：start → 24:00
                Schedule::create([
                    'shop_id' => $shopId,
                    'week' => $week,
                    'start_time' => $start,
                    'end_time' => 1440, // 24:00
                ]);

                // 第二段：00:00 → end ，週數 +1（如果是週日，第八天視為週一）
                $nextWeek = $week + 1;
                if ($nextWeek > 7) {
                    $nextWeek = 1;
                }

                Schedule::create([
                    'shop_id' => $shopId,
                    'week' => $nextWeek,
                    'start_time' => 0,
                    'end_time' => $end,
                ]);
            } else {
                // 非跨夜
                Schedule::create([
                    'shop_id' => $shopId,
                    'week' => $week,
                    'start_time' => $start,
                    'end_time' => $end,
                ]);
            }
        });
    }

    /**
     * 查詢時自動合併
     */
    public function getMergedSchedules($shopId)
    {
        $schedules = Schedule::where('shop_id', $shopId)
            ->orderBy('week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('week');

        $merged = [];

        foreach ($schedules as $week => $slots) {

            $temp = [];
            $current = null;

            foreach ($slots as $slot) {
                if ($current === null) {
                    $current = [
                        'start_time' => $slot->start_time,
                        'end_time' => $slot->end_time
                    ];
                    continue;
                }

                // 能合併（例：900~1200 + 1200~1500）
                if ($slot->start_time == $current['end_time']) {
                    $current['end_time'] = $slot->end_time;
                } else {
                    $temp[] = $current;
                    $current = [
                        'start_time' => $slot->start_time,
                        'end_time' => $slot->end_time
                    ];
                }
            }

            if ($current) {
                $temp[] = $current;
            }

            $merged[$week] = $temp;
        }

        return $merged;
    }
}
