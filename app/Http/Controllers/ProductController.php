<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;

class ProductController extends Controller
{
  //

  /**
   * 取得商店商品列表
   */
  public function index($shopId)
  {
    $shop = Shop::findOrFail($shopId);
    $products = $shop->products()->get();

    return response()->json([
      'status' => true,
      'message' => '取得商品列表成功',
      'data' => $products,
    ]);
  }


  /**
   * 新增商品
   */
  public function store(Request $request, $shopId)
  {
    $request->validate([
      'name' => 'required|string',
      'price' => 'required|numeric',
      'description' => 'nullable|string',
      'is_orderable' => 'required|boolean',
      'image_path' => 'nullable|string',
    ]);

    $shop = Shop::findOrFail($shopId);

    $product = $shop->products()->create([
      'name' => $request->name,
      'price' => $request->price,
      'description' => $request->description,
      'is_orderable' => $request->is_orderable,
      'image_path' => $request->image_path,
      'shop_id' => $shop->id,
    ]);

    return response()->json([
      'status' => true,
      'message' => '新增商品成功',
      'data' => $product,
    ]);
  }

  /**
   * 單筆商品
   */
  public function show($shopId, $id)
  {
    $shop = Shop::findOrFail($shopId);
    $product = $shop->products()->findOrFail($id);

    return response()->json([
      'status' => true,
      'message' => '取得商品成功',
      'data' => $product,
    ]);
  }

  /**
   * 更新商品
   */
  public function update(Request $request, $shopId, $id)
  {
    $request->validate([
      'name' => 'sometimes|required|string',
      'price' => 'sometimes|required|numeric',
      'description' => 'nullable|string',
    ]);

    $shop = Shop::findOrFail($shopId);
    $product = $shop->products()->findOrFail($id);

    $product->update($request->only(['name', 'price', 'description']));

    return response()->json([
      'status' => true,
      'message' => '更新商品成功',
      'data' => $product,
    ]);
  }

  /**
   * 刪除商品
   */
  public function destroy($shopId, $id)
  {
    $shop = Shop::findOrFail($shopId);
    $product = $shop->products()->findOrFail($id);

    $product->delete();

    return response()->json([
      'status' => true,
      'message' => '刪除商品成功',
    ]);
  }
}
