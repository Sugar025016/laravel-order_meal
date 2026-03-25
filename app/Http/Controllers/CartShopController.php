<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CartShop;
use App\Models\CartItem;


class CartShopController extends Controller
{

  public function index()
  {
    $user = auth()->user();

    // 取出該使用者的所有購物車商店 + 每個商店的商品 + 商品資訊
    $cartShops = CartShop::with([
      // 商品關聯
      'cartItems.product',

      // 商店關聯，只抓需要的欄位
      'shop' => function ($query) {
        $query->with('schedules'); // ← 加 schedules 
      }
    ])
      ->where('user_id', $user->id)
      ->get();

    return $this->success(
      '取得購物車資料',
      $cartShops
    );
  }


  // 取得單筆
  public function show($id)
  {
    $user = Auth::user();
    $cartShop = CartShop::with(['shop.schedules', 'cartItems.product'])->where('user_id', $user->id)->findOrFail($id);

    return response()->json([
      'status' => true,
      'data' => $cartShop
    ]);
  }
  // 新增到購物車

  public function store(Request $request)
  {

    $validated = $request->validate([
      'product_id' => 'required|exists:products,id',
      'qty' => 'required|integer|min:1',
      'remark' => 'nullable|string|max:255',
      'shop_id' => 'required|exists:shops,id',
    ]);

    $user = $request->user(); // 取得目前登入使用者
    DB::transaction(function () use ($user, $validated) {

      // 1️⃣ 取得或建立 CartShop
      $cartShop = CartShop::firstOrCreate(
        [
          'user_id' => $user->id,
          'shop_id' => $validated['shop_id']
        ]
      );

      // 2️⃣ 建立或更新 CartItem
      $cartItem = CartItem::firstOrNew([
        'cart_shop_id' => $cartShop->id,
        'product_id'   => $validated['product_id'],
        'remark'       => $validated['remark'] ?? null,
      ]);

      if ($cartItem->exists) {
        $cartItem->qty += $validated['qty'];
      } else {
        $cartItem->qty = $validated['qty'];
      }

      $cartItem->save();

      // 3️⃣ 新增完後，檢查是否超過 20 個 shop
      $cartShopIds = CartShop::where('user_id', $user->id)
        ->orderBy('created_at', 'asc') // 舊 → 新
        ->pluck('id');

      if ($cartShopIds->count() > 5) {
        $removeIds = $cartShopIds
          ->take($cartShopIds->count() - 5);

        CartItem::whereIn('cart_shop_id', $removeIds)->delete();
        CartShop::whereIn('id', $removeIds)->delete();
      }
    });

    return $this->success('商品已加入購物車', []);
  }

  // 更新購物車商品
  public function update(Request $request, $id)
  {
    $validated = $request->validate([
      'qty' => 'sometimes|required|integer|min:1',
      'remark' => 'nullable|string|max:255',
    ]);
    $user = Auth::user();
    // $cartItem = CartItem::where('user_id', $user->id)->findOrFail($id); 

    $cartItem = CartItem::whereHas('cartShop', function ($q) use ($user) {
      $q->where('user_id', $user->id);
    })->findOrFail($id);

    $cartItem->update($validated);

    return $this->success('購物車商品已更新', ['cart' => $cartItem]);
  }



  // 刪除購物車商品
  public function destroy($id)
  {
    $user = Auth::user();

    DB::transaction(function () use ($user, $id) {
      // 1️⃣ 取得購物車商品
      $cartItem = CartItem::whereHas('cartShop', function ($q) use ($user) {
        $q->where('user_id', $user->id);
      })->findOrFail($id);

      $cartShopId = $cartItem->cart_shop_id;

      // 2️⃣ 刪除商品
      $cartItem->delete();

      // 3️⃣ 檢查該 CartShop 是否還有商品
      $remainingItems = CartItem::where('cart_shop_id', $cartShopId)->count();

      // 4️⃣ 若沒有商品，刪除 CartShop
      if ($remainingItems === 0) {
        CartShop::where('id', $cartShopId)->delete();
      }
    });

    return $this->success('購物車商品已刪除');
  }


  // 刪除購物車Shop id 的 CartShop
  public function destroyCartShop($id)
  {
    $user = Auth::user();
    DB::transaction(function () use ($user, $id) {
      // 取得使用者對應商店的 CartShop
      $cartShop = CartShop::where('user_id', $user->id)
        ->where('id', $id)
        ->firstOrFail();

      // 刪除該 CartShop 的所有 CartItem
      CartItem::where('cart_shop_id', $cartShop->id)->delete();

      // 刪除 CartShop
      $cartShop->delete();
    });

    return $this->success('購物車商品已刪除');
  }
}
