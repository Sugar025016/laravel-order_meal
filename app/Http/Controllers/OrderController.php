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
  // public function index(Request $request)

  public function active()
  {
    $user = Auth::user();
    $query = Order::query()
      ->with(['items', 'shop'])
      ->where('user_id', $user->id)
      ->ongoing() // 👈 你已經有用 scope
      ->orderByDesc('id');

    return $this->success('成功', $query->get());
  }


  public function history(Request $request)
  {
    $validated = $request->validate([
      'per_page' => 'sometimes|integer|min:1|max:50',
      'page' => 'sometimes|integer|min:1',
    ]);

    $perPage = $validated['per_page'] ?? 10;
    $page = $validated['page'] ?? 1;

    $query = Order::query()
      ->with(['items', 'shop'])
      ->where('user_id', $request->user()->id)
      ->history() // 👈 你已經有用 scope
      ->orderByDesc('id')
      ->paginate($perPage, ['*'], 'page', $page);

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
      'scheduled_time' => 'nullable|date_format:Y-m-d H:i:s',
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
      $address = Address::where('id',  $user->current_address_id)
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
        // 'scheduled_time' => $validated['scheduled_time'] ?? null,
        'scheduled_time' => $validated['scheduled_time'] ?? null,
        'estimated_delivery_time' => $validated['estimated_delivery_time'] ?? null,

        'subtotal'      => $subtotal,
        'delivery_fee'  => $deliveryFee,
        'total_price'   => $subtotal + $deliveryFee,
        'status'        => Order::STATUS_PENDING,
      ]);
      $order->order_number = 'ORD' . now()->format('Ymd') . str_pad($order->id, 4, '0', STR_PAD_LEFT);
      $order->save();
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

    return $this->success('訂單建立成功', ['orderNumber' => $order->order_number], 201);
  }


  /**
   * 訂單詳情
   */
  public function show($orderNumber)
  {
    $order = Order::with(['items', 'shop'])
      ->where('user_id', auth()->id())
      ->where('order_number', $orderNumber)
      ->firstOrFail();

    return response()->json([
      'data' => $order
    ]);
  }


  public function ongoingCount()
  {
    $ordersCount = Order::where('user_id', auth()->id())->ongoing()->count();
    return response()->json([
      'data' => $ordersCount
    ]);
  }

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
