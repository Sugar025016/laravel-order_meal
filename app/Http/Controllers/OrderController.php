<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CartShop;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
  public function ongoing()
  {
    $user = Auth::user();
    $orders = Order::query()
      ->with(['items', 'shop'])
      ->where('user_id', $user->id)
      ->ongoing()
      ->orderByDesc('id');
    return $this->success('成功', $orders);
  }
  /**
   * 訂單列表（可依使用者 / 狀態）
   */
  public function index(Request $request)
  {
    $validated = $request->validate([
      'per_page' => 'sometimes|integer|min:1|max:50',
      'page' => 'sometimes|integer|min:1',
    ]);
    $perPage = $validated['per_page'] ?? 10;
    $query = Order::query()
      ->with(['items', 'shop'])
      ->where('user_id', $request->user()->id)
      ->history()
      ->orderByDesc('id')
      ->paginate($perPage, ['*'], 'page', $validated['page'] ?? 1);

    return $this->success('成功', $query);
  }

  /**
   * 建立訂單（結帳）
   */
  public function store(int $cartShopId, Request $request)
  {

    Log::info('add order request', [
      'request' => $request->all(),
      'cartShopId' => $cartShopId
    ]);
    $validated = $request->validate([
      'pay_method'    => 'required|integer|in:1,2,3',
      'delivery_type' => 'required|integer|in:1,2',
      'order_type'    => 'required|integer|in:1,2',
      'is_cutlery'    => 'boolean',
      'customer_note' => 'nullable|string',
      'estimated_delivery_time' => 'nullable|date_format:Y-m-d H:i:s',
    ]);

    $user = $request->user();

    $order = DB::transaction(function () use ($validated, $user, $cartShopId) {

      // 1️⃣ 取得購物車（一定要驗證 user）
      $cartShop = CartShop::with(['shop', 'cartItems.product'])
        ->where('id', $cartShopId)
        ->where('user_id', $user->id)
        ->firstOrFail();

      // 2️⃣ 取得地址（快照）
      $address = Address::where('id',  $user->default_address_id)
        ->where('user_id', $user->id)
        ->firstOrFail();

      // 3️⃣ 計算商品小計
      $subtotal = 0;

      foreach ($cartShop->cartItems as $cartItem) {
        $subtotal += $cartItem->product->price * $cartItem->qty;
      }

      $deliveryFee = $validated['delivery_type'] == 1 ? 50 : 0;

      // 4️⃣ 建立 Order（快照）
      $order = Order::create([
        'user_id'       => $user->id,
        'shop_id'       => $cartShop->shop_id,

        'order_type'    => $validated['order_type'],
        'delivery_type' => $validated['delivery_type'],
        'pay_method'    => $validated['pay_method'],
        'is_cutlery'    => $validated['is_cutlery'] ?? false,

        'city'   => $address->city,
        'area'   => $address->area,
        'street' => $address->street,
        'detail' => $address->detail,
        'lat'    => $address->lat,
        'lng'    => $address->lng,

        'customer_note' => $validated['customer_note'] ?? null,
        'estimated_delivery_time' => $validated['estimated_delivery_time'] ?? null,

        'subtotal'      => $subtotal,
        'delivery_fee'  => $deliveryFee,
        'total_price'   => $subtotal + $deliveryFee,
        'status'        => Order::STATUS_PENDING,
      ]);

      // 5️⃣ cart_items → order_items
      foreach ($cartShop->cartItems as $cartItem) {
        OrderItem::create([
          'order_id'      => $order->id,
          'product_id'    => $cartItem->product_id,
          'product_name'  => $cartItem->product->name,
          'product_price' => $cartItem->product->price,
          'quantity'      => $cartItem->qty,
          'subtotal'      => $cartItem->product->price * $cartItem->qty,
          'customer_note' => $cartItem->note,
        ]);
      }

      // 6️⃣ 清空購物車（避免重複下單）
      $cartShop->cartItems()->delete();
      $cartShop->delete();

      return $order;
    });

    return $this->success('訂單建立成功', $order->load('items'), 201);
  }


  /**
   * 訂單詳情
   */
  public function show($id)
  {
    $order = Order::with(['items', 'shop'])
      ->where('user_id', auth()->id())
      ->findOrFail($id);

    return response()->json([
      'data' => $order
    ]);
  }

  // /**
  //  * 更新訂單狀態（店家 / 系統）
  //  */
  // public function updateStatus(Request $request, $id)
  // {
  //   $validated = $request->validate([
  //     'status' => 'required|integer|in:2,3,4,5,6,7'
  //   ]);

  //   $order = Order::findOrFail($id);

  //   // 簡單狀態流轉防呆（面試加分）
  //   if ($validated['status'] < $order->status) {
  //     return response()->json([
  //       'message' => '訂單狀態不可倒退'
  //     ], 422);
  //   }

  //   $order->update([
  //     'status' => $validated['status']
  //   ]);

  //   return response()->json([
  //     'message' => '訂單狀態已更新'
  //   ]);
  // }

  /**
   * 取消訂單
   */
  public function cancel($id)
  {
    $order = Order::where('user_id', auth()->id())->findOrFail($id);

    if ($order->status >= Order::STATUS_COOKING) {
      return response()->json([
        'message' => '訂單已開始製作，無法取消'
      ], 422);
    }

    $order->update([
      'status' => Order::STATUS_CANCELED
    ]);

    return response()->json([
      'message' => '訂單已取消'
    ]);
  }
}
