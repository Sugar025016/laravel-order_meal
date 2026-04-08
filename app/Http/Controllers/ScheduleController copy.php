<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;

class ScheduleController extends Controller
{
  // 取得某店家的所有排程
  public function index($shopId)
  {
    // 1. 取出該店所有排程
    $schedules = Schedule::where('shop_id', $shopId)
      ->orderBy('start_time')
      ->get()
      ->toArray();

    $merged = [];
    $crossWeekEndIndex = null;
    $crossWeekStartIndex = null;

    // 2. 找出 end_time = 10080 的時段
    foreach ($schedules as $key => $schedule) {
      if ($schedule['end_time'] == 10080) {
        $crossWeekEndIndex = $key;
      }
      if ($schedule['start_time'] == 0) {
        $crossWeekStartIndex = $key;
      }
    }

    // 3. 如果兩個時段都存在，合併
    if ($crossWeekEndIndex !== null && $crossWeekStartIndex !== null) {
      $newSegment = [
        'start_time' => $schedules[$crossWeekEndIndex]['start_time'],
        'end_time' => $schedules[$crossWeekStartIndex]['end_time'] + 10080, // 跨週加上週長
      ];

      // 移除原本兩段
      unset($schedules[$crossWeekEndIndex], $schedules[$crossWeekStartIndex]);

      // 將合併段加入
      $schedules[] = $newSegment;
    }

    // 4. 其他時間段保持原樣
    $merged = array_values($schedules); // 重建索引

    return response()->json($merged);
  }
  // 取得單筆排程
  public function show($shopId, $id)
  {
    $schedule = Schedule::where('shop_id', $shopId)->findOrFail($id);
    return response()->json($schedule);
  }

  // 新增排程

  /**
   * 更新商家排程
   */
  public function store(Request $request, $shopId)
  {
    $validatedData = $request->validate([
      '*.week' => 'required|integer|min:1|max:7',
      '*.start_time' => 'required|integer|min:0',
      '*.end_time'   => 'required|integer|gt:start_time|max:11520',
    ]);

    $segments = [];

    // 1. 先拆跨週時間
    foreach ($validatedData as $item) {
      $start = $item['start_time'];
      $end   = $item['end_time'];

      if ($end > 10080) { // 超過總週分鐘，拆到下一週
        $segments[] = [
          'start' => $start,
          'end'   => 10080, // 週末
        ];
        $segments[] = [
          'start' => 0,
          'end'   => $end - 10080,
        ];
      } else {
        $segments[] = [
          'start' => $start,
          'end'   => $end,
        ];
      }
    }

    // 2. 檢查時間重疊（不分 week）
    usort($segments, fn($a, $b) => $a['start'] <=> $b['start']); // 先排序

    $lastEnd = null;
    foreach ($segments as $seg) {
      if ($lastEnd !== null && $seg['start'] < $lastEnd) {
        throw new \Exception("時間段重疊：{$seg['start']}~{$seg['end']}");
      }
      $lastEnd = $seg['end'];
    }

    DB::transaction(function () use ($shopId, $segments) {

      // 3. 刪掉舊排程
      Schedule::where('shop_id', $shopId)->delete();

      // 4. 新增排程
      foreach ($segments as $seg) {
        Schedule::create([
          'week'       =>  $seg['start'] / 1440 + 1, // 計算週數
          'shop_id'    => $shopId,
          'start_time' => $seg['start'],
          'end_time'   => $seg['end'],
        ]);
      }
    });

    return response()->json([
      'status' => true,
      'message' => '商家排程已更新',
      'data' => $segments,
    ]);
  }


  // 刪除排程
  public function destroy($shopId, $id)
  {
    $schedule = Schedule::where('shop_id', $shopId)->findOrFail($id);
    $schedule->delete();

    return response()->json([
      'status' => true,
      'message' => '刪除成功'
    ]);
  }
}
