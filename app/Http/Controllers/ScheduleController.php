<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;

class ScheduleController extends Controller
{
  // 取得某店家的所有排程
  public function index($shopId)
  {
    $schedules = Schedule::where('shop_id', $shopId)->get();
    return response()->json($schedules);
  }

  // 取得單筆排程
  public function show($shopId, $id)
  {
    $schedule = Schedule::where('shop_id', $shopId)->findOrFail($id);
    return response()->json($schedule);
  }

  // 新增排程
  public function store(Request $request, $shopId)
  {
    $validatedData = $request->validate([
      '*.week' => 'required|integer|min:1|max:7',
      '*.start_time' => 'required|date_format:H:i',
      '*.end_time' => 'required|date_format:H:i|after:start_time',
    ]);

    // 刪掉該商家所有舊排程
    Schedule::where('shop_id', $shopId)->delete();

    $results = [];

    // 新增所有排程
    foreach ($validatedData as $item) {
      $schedule = Schedule::create(array_merge($item, ['shop_id' => $shopId]));
      $results[] = $schedule;
    }

    return response()->json([
      'status' => true,
      'message' => '商家排程已更新',
      'data' => $results,
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
