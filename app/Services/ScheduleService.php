<?php

namespace App\Services;

use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleService
{
    /**
     * 新增排程（自動處理跨夜拆分）
     */
    // public function creat





    const WEEK_MINUTES = 10080;
    const DAY_MINUTES  = 1440;

    /**
     * 合併跨週時間（index 用）
     */
    public function mergeCrossWeek(array $schedules): array
    {
        $crossWeekEndIndex = null;
        $crossWeekStartIndex = null;
        // Log::info('mergeCrossWeek start', [
        //     'count' => count($schedules),
        // ]);
        Log::info('schedules------------', [
            'value' => $schedules,
        ]);



        foreach ($schedules as $key => $schedule) {
            Log::info('schedules------------', [
                'value' => $schedule['start_time'] . ' ~ ' . $schedule['end_time'],
            ]);
            if ($schedule['end_time'] == self::WEEK_MINUTES) {
                $crossWeekEndIndex = $key;
            }
            if ($schedule['start_time'] == 0) {
                $crossWeekStartIndex = $key;
            }

            // Log::info("訊息crossWeekEndIndex:-----------",  ['index' => $crossWeekStartIndex]);
        }


        Log::info(' $crossWeekEndIndex  ~  $crossWeekStartIndex----------', [
            'value' => $crossWeekEndIndex . ' ~ ' . $crossWeekStartIndex,
        ]);

        if ($crossWeekEndIndex !== null && $crossWeekStartIndex !== null) {
            // Log::info("訊息crossWeekEndIndex:", $crossWeekStartIndex);
            // Log::info("訊息crossWeekStartIndex:", $crossWeekEndIndex);
            // echo  "Merging cross-week schedules\n" . $crossWeekStartIndex . " , " . $crossWeekEndIndex;
            // dump("Merging cross-week schedules\n");
            // dump($crossWeekStartIndex);
            // dump($crossWeekEndIndex);
            $newSegment = [
                'id'         => null, // 或 'merged'
                'shop_id'    => $schedules[$crossWeekEndIndex]['shop_id'],
                'week'       => 7,
                'start_time' => $schedules[$crossWeekEndIndex]['start_time'],
                'end_time'   => $schedules[$crossWeekStartIndex]['end_time'] + self::WEEK_MINUTES,
            ];

            unset($schedules[$crossWeekEndIndex], $schedules[$crossWeekStartIndex]);
            $schedules[] = $newSegment;
        }
        Log::info('array_values------------', [
            'value' => $schedules,
        ]);
        return array_values($schedules);
    }

    /**
     * 拆跨週時間（store 用）
     */
    public function splitCrossWeek(array $items): array
    {
        $segments = [];

        foreach ($items as $item) {
            $start = $item['start_time'];
            $end   = $item['end_time'];

            if ($end > self::WEEK_MINUTES) {
                $segments[] = ['start' => $start, 'end' => self::WEEK_MINUTES];
                $segments[] = ['start' => 0, 'end' => $end - self::WEEK_MINUTES];
            } else {
                $segments[] = ['start' => $start, 'end' => $end];
            }
        }

        return $segments;
    }

    /**
     * 檢查時間是否重疊
     */
    public function assertNoOverlap(array $segments): void
    {
        usort($segments, fn($a, $b) => $a['start'] <=> $b['start']);

        $lastEnd = null;
        foreach ($segments as $seg) {
            if ($lastEnd !== null && $seg['start'] < $lastEnd) {
                throw new \Exception("時間段重疊：{$seg['start']}~{$seg['end']}");
            }
            $lastEnd = $seg['end'];
        }
    }

    /**
     * 計算 week（1~7）
     */
    public function calcWeek(int $startMinute): int
    {
        return intdiv($startMinute, self::DAY_MINUTES) + 1;
    }
}
