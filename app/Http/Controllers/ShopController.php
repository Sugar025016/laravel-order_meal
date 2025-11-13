<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use Illuminate\Validation\Rule;

class ShopController extends Controller
{
  // 取得列表（可搜尋）
  public function index(Request $request)
  {
    // 🔹 接收查詢參數
    $city = $request->input('city');
    $area = $request->input('area');
    $category = $request->input('category');
    $keyword = $request->input('keyword');

    // 🔹 建立查詢
    $query = Shop::query()
      ->with(['categories']); // 預先載入關聯分類

    // 🔹 城市搜尋
    if (!empty($city)) {
      $query->where('city', 'like', "%{$city}%");
    }

    // 🔹 地區搜尋
    if (!empty($area)) {
      $query->where('area', 'like', "%{$area}%");
    }

    // 🔹 分類搜尋（可用分類 ID 或名稱）
    if (!empty($category)) {
      $query->whereHas('categories', function ($q) use ($category) {
        $q->where('categories.id', $category)
          ->orWhere('categories.name', 'like', "%{$category}%");
      });
    }

    // 🔹 關鍵字搜尋（同時搜尋店名 + 分類名）
    if (!empty($keyword)) {
      $query->where(function ($q) use ($keyword) {
        $q->where('brand', 'like', "%{$keyword}%")
          ->orWhere('branch', 'like', "%{$keyword}%")
          ->orWhereHas('categories', function ($sub) use ($keyword) {
            $sub->where('categories.name', 'like', "%{$keyword}%");
          })
          ->orWhereHas('products', function ($sub) use ($keyword) {
            $sub->where('name', 'like', "%{$keyword}%");
          });
      });
    }

    // 🔹 取得結果
    $shops = $query->get();

    // 🔹 指定回傳欄位
    $fields = [
      'id',
      'brand',
      'branch',
      'phone',
      'description',
      'is_orderable',
      'image_path',
      'city',
      'area',
      'street',
      'detail',
    ];

    $shops = $this->transformData($shops, $fields);

    // 🔹 回傳 JSON
    return $this->success('取得商家列表成功', $shops);
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
      $request->only([
        'brand',
        'branch',
        'phone',
        'city',
        'area',
        'street',
        'detail'
      ]),
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

    $shop = Shop::with(['tabs.products', 'schedules'])->find($id);
    return $this->success('取得店家資料成功',  $shop);
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
