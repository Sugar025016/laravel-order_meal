<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tab;
use App\Models\Shop;

class TabController extends Controller
{
  // ✅ 取得某商店的所有 tab
  public function index($shopId)
  {
    $shop = Shop::findOrFail($shopId);
    $tabs = $shop->tabs()->with('products')->get();

    return response()->json($tabs);
  }

  // ✅ 新增 tab
  public function store(Request $request, $shopId)
  {
    $shop = Shop::findOrFail($shopId);

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'is_show' => 'boolean',
      'product_ids' => 'array',
      'product_ids.*' => 'integer|exists:products,id',
    ]);

    // 建立 tab
    $tab = $shop->tabs()->create([
      'name' => $validated['name'],
      'is_show' => $validated['is_show'] ?? false,
    ]);

    // 若有指定商品，建立關聯
    if (!empty($validated['product_ids'])) {
      $tab->products()->sync($validated['product_ids']);
    }

    return response()->json([
      'status' => true,
      'message' => 'Tab created successfully',
      'data' => $tab->load('products')
    ], 201);
  }

  // ✅ 查詢單筆 tab
  public function show($shopId, $id)
  {
    $tab = Tab::where('shop_id', $shopId)
      ->with('products')
      ->findOrFail($id);

    return response()->json($tab);
  }

  // ✅ 更新 tab
  public function update(Request $request, $shopId, $id)
  {
    $tab = Tab::where('shop_id', $shopId)->findOrFail($id);

    $validated = $request->validate([
      'name' => 'sometimes|string|max:255',
      'is_show' => 'sometimes|boolean',
      'product_ids' => 'array',
      'product_ids.*' => 'integer|exists:products,id',
    ]);

    $tab->update($validated);

    if (isset($validated['product_ids'])) {
      $tab->products()->sync($validated['product_ids']);
    }

    return response()->json([
      'status' => true,
      'message' => 'Tab updated successfully',
      'data' => $tab->load('products')
    ]);
  }

  // ✅ 刪除 tab
  public function destroy($shopId, $id)
  {
    $tab = Tab::where('shop_id', $shopId)->findOrFail($id);
    $tab->delete();

    return response()->json([
      'status' => true,
      'message' => 'Tab deleted successfully'
    ]);
  }
}
