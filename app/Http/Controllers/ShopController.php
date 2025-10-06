<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use Illuminate\Validation\Rule;

class ShopController extends Controller
{
  // 取得列表
  public function index()
  {

    $shop = Shop::with(['categories', 'products'])->get();
    return response()->json($shop);
  }

  // 新增
  public function store(Request $request, CaptchaController $captcha)
  {
    // $captchaResult = $captcha->verify($request);
    // if ($captchaResult !== true) {
    //   // 驗證碼錯誤，直接回傳
    //   return $captchaResult;
    // }
    // return 'ok';
    $request->validate([
      'brand' => [
        'required',
        'string',
        Rule::unique('shops')->where(function ($query) use ($request) {
          $query->where('branch', $request->branch);
        }),
      ],
      'branch' => 'required|string',
      'phone' => 'required|string',
      'address_data_id' => 'required|numeric',
      'detail' => 'required|string',
    ]);

    $shop = Shop::create(array_merge(
      $request->only(['brand', 'branch', 'phone', 'address_data_id', 'detail']),
      [
        'user_id' => $request->user()->id,
      ]
    ));

    return response()->json([
      'message' => '店舖已新增',
      'data' => $shop
    ], 201);
  }

  // 單筆
  public function show($id)
  {
    $shop = Shop::findOrFail($id);
    return response()->json($shop);
  }

  // 更新
  public function update(Request $request, $id)
  {
    $shop = Shop::findOrFail($id);
    $request->validate([
      'brand' => [
        'required',
        'string',
        Rule::unique('shops')->where(function ($query) use ($request) {
          $query->where('branch', $request->branch);
        }),
      ],
      'branch' => 'required|string',
      'phone' => 'required|string',
      'description' => 'required|string',
      'is_orderable' => 'required|boolean',
      'is_open' => 'required|boolean',
      'delivery_km' => 'required|numeric',
      'delivery_price' => 'required|numeric',
      'image_path' => 'required|string',
    ]);

    $shop->update($request->all());

    return response()->json([
      'message' => '店舖已更新',
      'data' => $shop
    ]);
  }

  // 刪除
  public function destroy($id)
  {
    $shop = Shop::findOrFail($id);
    $shop->delete();

    return response()->json([
      'message' => '店舖已刪除'
    ]);
  }

  /**
   * 為商店設定分類（多對多關聯）
   */
  public function assignToShop(Request $request, $shopId)
  {
    $request->validate([
      'category_ids' => 'required|array',
      'category_ids.*' => 'exists:categories,id'
    ]);

    $shop = Shop::findOrFail($shopId);
    $shop->categories()->sync($request->category_ids);
    $shop->load('categories');


    return $this->success('分類設定成功', ['shop' => $shop]);
  }
}
