<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CartController extends Controller
{
  // 取得使用者的購物車
  public function index()
  {
    $user = Auth::user();
    $carts = Cart::with('product')->where('user_id', $user->id)->get();


    return $this->success('取得購物車列表成功', ['carts' => $carts]);
  }

  // 取得單筆
  public function show($id)
  {
    $user = Auth::user();
    $cart = Cart::with('product')->where('user_id', $user->id)->findOrFail($id);

    return response()->json([
      'status' => true,
      'data' => $cart
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

    $validated['user_id'] = auth()->id(); // ✅ 自動填入登入使用者ID

    $cart = Cart::create($validated);
    return $this->success('商品已加入購物車', ['cart' => $cart]);
  }

  // 更新購物車商品
  public function update(Request $request, $id)
  {
    $user = Auth::user();
    $cart = Cart::where('user_id', $user->id)->findOrFail($id);

    $validated = $request->validate([
      'qty' => 'sometimes|required|integer|min:1',
      'remark' => 'nullable|string|max:255',
    ]);

    $cart->update($validated);


    return $this->success('購物車商品已更新', ['cart' => $cart]);
  }

  // 刪除購物車商品
  public function destroy($id)
  {
    $user = Auth::user();
    $cart = Cart::where('user_id', $user->id)->findOrFail($id);
    $cart->delete();

    return $this->success('購物車商品已刪除');
  }
}
