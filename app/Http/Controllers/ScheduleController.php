<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;
use App\Services\ScheduleService;

class ScheduleController extends Controller
{

  public function index($shopId, ScheduleService $timeService)
  {
    $schedules = Schedule::where('shop_id', $shopId)
      ->orderBy('start_time')
      ->get()
      ->toArray();

    $merged = $timeService->mergeCrossWeek($schedules);

    return response()->json($merged);
  }
  public function store(Request $request, $shopId, ScheduleService $timeService)
  {
    $validatedData = $request->validate([
      '*.week' => 'required|integer|min:1|max:7',
      '*.start_time' => 'required|integer|min:0',
      '*.end_time'   => 'required|integer|gt:start_time|max:11520',
    ]);

    // 1. 拆跨週
    $segments = $timeService->splitCrossWeek($validatedData);

    // 2. 檢查重疊
    $timeService->assertNoOverlap($segments);

    DB::transaction(function () use ($shopId, $segments, $timeService) {

      Schedule::where('shop_id', $shopId)->delete();

      foreach ($segments as $seg) {
        Schedule::create([
          'shop_id'    => $shopId,
          'week'       => $timeService->calcWeek($seg['start']),
          'start_time' => $seg['start'],
          'end_time'   => $seg['end'],
        ]);
      }
    });

    return response()->json([
      'status'  => true,
      'message' => '商家排程已更新',
      'data'    => $segments,
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
