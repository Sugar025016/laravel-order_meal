<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;

class OrderController extends Controller
{
  //
  /**
   * 新增訂單（從購物車結帳）
   */
  public function store(Request $request, $shopId)
  {
    $user = auth()->user();

    // 驗證前端輸入
    $validated = $request->validate([
      'remark' => 'nullable|string|max:255',
      'take_remark' => 'nullable|string|max:255',
      'take_time' => 'date_format:Y-m-d H:i',
      'payment_method' => 'required|in:cash,credit,linepay',
    ]);

    // 取得該使用者在該商家的購物車項目
    $cartItems = Cart::where('user_id', $user->id)
      ->where('shop_id', $shopId)
      ->get();

    // return $user->currentAddress;
    if ($user->currentAddress === null) {
      return response()->json([
        'status' => false,
        'message' => '目前地址是空的，無法建立訂單',
      ], 400);
    }


    if ($cartItems->isEmpty()) {
      return response()->json([
        'status' => false,
        'message' => '購物車是空的，無法建立訂單',
      ], 400);
    }

    DB::beginTransaction();
    try {


      // 建立訂單
      $order = Order::create(array_merge($validated, [
        'shop_id' => $shopId,
        'user_id' => $user->id,
        'phone' => $user->phone,
        'city' => $user->currentAddress?->city ?? '',
        'area' => $user->currentAddress?->area ?? '',
        'street' => $user->currentAddress?->street ?? '',
        'detail' => $user->currentAddress?->detail ?? '',
        'lat' => $user->currentAddress?->lat ?? 0,
        'lng' => $user->currentAddress?->lng ?? 0,
        'status' => 'pending', // 預設為 pending
      ]));
      // return $cartItems->first()->product;
      // return $order->id;

      // 建立訂單明細
      foreach ($cartItems as $item) {
        OrderDetail::create([
          'order_id' => $order->id,
          'product_id' => $item->product_id,
          'product_name' => $item->product->name ?? '',
          'product_price' => $item->product->price ?? 0,
          'qty' => $item->qty,
          'remark' => $item->remark,
        ]);
      }

      // 清空購物車
      Cart::where('user_id', $user->id)
        ->where('shop_id', $shopId)
        ->delete();

      DB::commit();

      return response()->json([
        'status' => true,
        'message' => '訂單建立成功',
        'data' => $order->load('orderDetails'),
      ]);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json([
        'status' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }
  /**
   * 取得使用者訂單列表
   */
  public function index(Request $request)
  {
    $user = auth()->user();

    $orders = Order::where('user_id', $user->id)
      ->with(['shop', 'orderDetails.product'])
      ->get();

    return response()->json([
      'status' => true,
      'data' => $orders
    ]);
  }

  /**
   * 單筆訂單
   */
  public function show($id)
  {
    $user = auth()->user();

    $order = Order::where('user_id', $user->id)
      ->with(['shop', 'orderDetails.product'])
      ->find($id);

    if (!$order) {
      return response()->json([
        'status' => false,
        'message' => '訂單不存在'
      ], 404);
    }

    return response()->json([
      'status' => true,
      'data' => $order
    ]);
  }

  /**
   * 更新訂單（例如修改狀態或備註）
   */
  public function update(Request $request, $id)
  {
    $user = auth()->user();

    $order = Order::where('user_id', $user->id)->find($id);

    if (!$order) {
      return response()->json([
        'status' => false,
        'message' => '訂單不存在'
      ], 404);
    }

    $validated = $request->validate([
      'status' => 'nullable|in:pending,paid,delivered,cancelled',
      'remark' => 'nullable|string|max:255',
      'take_remark' => 'nullable|string|max:255',
    ]);

    $order->update($validated);

    return response()->json([
      'status' => true,
      'message' => '訂單更新成功',
      'data' => $order
    ]);
  }

  /**
   * 刪除訂單（軟刪除）
   */
  public function destroy($id)
  {
    $user = auth()->user();

    $order = Order::where('user_id', $user->id)->find($id);

    if (!$order) {
      return response()->json([
        'status' => false,
        'message' => '訂單不存在'
      ], 404);
    }

    $order->delete();

    return response()->json([
      'status' => true,
      'message' => '訂單已刪除'
    ]);
  }
}
