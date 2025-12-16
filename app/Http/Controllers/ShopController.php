<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
  // 取得列表（可搜尋）
  // public function index(Request $request)
  // {
  //   // 🔹 接收查詢參數
  //   $city = $request->input('city');
  //   $area = $request->input('area');
  //   $category = $request->input('category');
  //   $keyword = $request->input('keyword');


  //   // 🔹 建立查詢
  //   $query = Shop::query()
  //     ->with(['categories']); // 預先載入關聯分類
  //   $user = $request->user();

  //   if ($user && $user->currentAddress) {

  //     $userLat = $user->currentAddress->lat;
  //     $userLng = $user->currentAddress->lng;
  //     $maxKm = env('DELIVERY_MAX_KM', 10);
  //     $latRange = $maxKm / 111;
  //     $lngRange = $maxKm / (111 * cos(deg2rad($userLat)));

  //     $query->whereBetween('lat', [$userLat - $latRange, $userLat + $latRange])
  //       ->whereBetween('lng', [$userLng - $lngRange, $userLng + $lngRange])
  //       ->selectRaw("
  //           shops.*,
  //           (
  //               6371 * acos(
  //                   cos(radians(?)) *
  //                   cos(radians(shops.lat)) *
  //                   cos(radians(shops.lng) - radians(?)) +
  //                   sin(radians(?)) *
  //                   sin(radians(shops.lat))
  //               )
  //           ) AS distance
  //         ", [$userLat, $userLng, $userLat])
  //       ->having('distance', '<=', DB::raw('delivery_km'))
  //       ->orderBy('distance', 'asc');
  //   } else {
  //     // 🔹 城市搜尋
  //     if (!empty($city)) {
  //       $query->where('city', 'like', "%{$city}%");
  //     }

  //     // 🔹 地區搜尋
  //     if (!empty($area)) {
  //       $query->where('area', 'like', "%{$area}%");
  //     }
  //   }


  //   // 🔹 分類搜尋（可用分類 ID 或名稱）
  //   // if (!empty($category)) {
  //   //   $query->whereHas('categories', function ($q) use ($category) {
  //   //     $q->where('categories.id', $category)
  //   //       ->orWhere('categories.name', 'like', "%{$category}%");
  //   //   });
  //   // }

  //   // // 🔹 關鍵字搜尋（同時搜尋店名 + 分類名）
  //   // if (!empty($keyword)) {
  //   //   $query->where(function ($q) use ($keyword) {
  //   //     $q->where('brand', 'like', "%{$keyword}%")
  //   //       ->orWhere('branch', 'like', "%{$keyword}%")
  //   //       ->orWhereHas('categories', function ($sub) use ($keyword) {
  //   //         $sub->where('categories.name', 'like', "%{$keyword}%");
  //   //       })
  //   //       ->orWhereHas('products', function ($sub) use ($keyword) {
  //   //         $sub->where('name', 'like', "%{$keyword}%");
  //   //       });
  //   //   });
  //   // }

  //   // 🔹 取得結果
  //   $shops = $query->get();

  //   // 🔹 指定回傳欄位
  //   $fields = [
  //     'id',
  //     'brand',
  //     'branch',
  //     'phone',
  //     'description',
  //     'is_orderable',
  //     'image_path',
  //     'city',
  //     'area',
  //     'street',
  //     'detail',
  //   ];

  //   $shops = $this->transformData($shops, $fields);

  //   // 🔹 回傳 JSON
  //   return $this->success('取得商家列表成功', $shops);
  // }
  // public function index(Request $request)
  // {
  //   $request->validate([
  //     'city' => 'nullable|string|max:50',
  //     'area' => 'nullable|string|max:50',
  //     'category' => 'nullable|integer',
  //     'keyword' => 'nullable|string|max:100',
  //     'lat' => 'nullable|numeric|between:-90,90',
  //     'lng' => 'nullable|numeric|between:-180,180',
  //   ]);

  //   $city = $request->input('city');
  //   $area = $request->input('area');
  //   $category = $request->input('category');
  //   $keyword = $request->input('keyword');
  //   $userLat = $request->input('lat');
  //   $userLng = $request->input('lng');

  //   $query = Shop::query()->with(['categories', 'schedules']);

  //   if ($userLat && $userLng) {
  //     $maxKm = env('DELIVERY_MAX_KM', 10);
  //     $latRange = $maxKm / 111;
  //     $lngRange = $maxKm / (111 * cos(deg2rad($userLat)));

  //     $query->whereBetween('lat', [$userLat - $latRange, $userLat + $latRange])
  //       ->whereBetween('lng', [$userLng - $lngRange, $userLng + $lngRange])
  //       ->selectRaw("
  //               shops.*,
  //               (
  //                   6371 * acos(
  //                       cos(radians(?)) *
  //                       cos(radians(shops.lat)) *
  //                       cos(radians(shops.lng) - radians(?)) +
  //                       sin(radians(?)) *
  //                       sin(radians(shops.lat))
  //                   )
  //               ) AS distance
  //           ", [$userLat, $userLng, $userLat])
  //       ->havingRaw('distance <= delivery_km')
  //       ->orderBy('distance', 'asc');
  //   } else {
  //     if ($city) $query->where('city', 'like', "%{$city}%");
  //     if ($area) $query->where('area', 'like', "%{$area}%");
  //   }

  //   if ($category) {
  //     $query->whereHas('categories', function ($q) use ($category) {
  //       $q->where('categories.id', $category)
  //         ->orWhere('categories.name', 'like', "%{$category}%");
  //     });
  //   }

  //   if ($keyword) {
  //     $query->where(function ($q) use ($keyword) {
  //       $q->where('brand', 'like', "%{$keyword}%")
  //         ->orWhere('branch', 'like', "%{$keyword}%")
  //         ->orWhereHas('categories', fn($sub) => $sub->where('categories.name', 'like', "%{$keyword}%"))
  //         ->orWhereHas('products', fn($sub) => $sub->where('name', 'like', "%{$keyword}%"));
  //     });
  //   }

  //   $shops = $query->get();

  //   return $this->success(
  //     '取得商家列表成功',
  //     $shops
  //   );
  // }

  // public function index(Request $request)
  // {
  //   $request->validate([
  //     'city' => 'nullable|string|max:50',
  //     'area' => 'nullable|string|max:50',
  //     'category' => 'nullable|integer',
  //     'keyword' => 'nullable|string|max:100',
  //     'lat'      => 'nullable|numeric|between:-90,90',
  //     'lng'      => 'nullable|numeric|between:-180,180',
  //   ]);

  //   $city     = $request->input('city');
  //   $area     = $request->input('area');
  //   $category = $request->input('category');
  //   $keyword  = $request->input('keyword');
  //   $userLat  = $request->input('lat');
  //   $userLng  = $request->input('lng');

  //   // ===== 時間計算 =====
  //   $now = now();
  //   $currentWeek     = $now->dayOfWeekIso; // 1~7
  //   $yesterdayWeek   = $now->copy()->subDay()->dayOfWeekIso;
  //   $currentMinutes  = $now->hour * 60 + $now->minute;

  //   // ===== Shop Query =====
  //   $query = Shop::query()
  //     ->with(['categories'])
  //     ->select('shops.*')
  //     ->selectRaw("
  //         COALESCE((
  //           SELECT COUNT(*)
  //           FROM schedules
  //           WHERE schedules.shop_id = shops.id
  //           AND (
  //               (schedules.week = ? AND schedules.start_time <= ? AND schedules.end_time > ?)
  //               OR (schedules.week = ? AND schedules.end_time - 1440 > ?)
  //           )
  //         ), 0) AS is_open_by_schedule
  //       ", [$currentWeek, $currentMinutes, $currentMinutes, $yesterdayWeek, $currentMinutes]);

  //   // ===== 距離篩選 =====
  //   if ($userLat && $userLng) {
  //     $maxKm = env('DELIVERY_MAX_KM', 10);

  //     $latRange = $maxKm / 111;
  //     $lngRange = $maxKm / (111 * cos(deg2rad($userLat)));

  //     $query->whereBetween('lat', [$userLat - $latRange, $userLat + $latRange])
  //       ->whereBetween('lng', [$userLng - $lngRange, $userLng + $lngRange])
  //       ->selectRaw("
  //               (
  //                   6371 * acos(
  //                       cos(radians(?)) *
  //                       cos(radians(shops.lat)) *
  //                       cos(radians(shops.lng) - radians(?)) +
  //                       sin(radians(?)) *
  //                       sin(radians(shops.lat))
  //                   )
  //               ) AS distance
  //           ", [$userLat, $userLng, $userLat])
  //       ->havingRaw('distance <= delivery_km')
  //       ->orderBy('distance', 'asc');
  //   } else {
  //     if ($city) $query->where('city', 'like', "%{$city}%");
  //     if ($area) $query->where('area', 'like', "%{$area}%");
  //   }

  //   // ===== 類別篩選 =====
  //   if ($category) {
  //     $query->whereHas('categories', function ($q) use ($category) {
  //       $q->where('categories.id', $category)
  //         ->orWhere('categories.name', 'like', "%{$category}%");
  //     });
  //   }

  //   // ===== 關鍵字篩選 =====
  //   if ($keyword) {
  //     $query->where(function ($q) use ($keyword) {
  //       $q->where('brand', 'like', "%{$keyword}%")
  //         ->orWhere('branch', 'like', "%{$keyword}%")
  //         ->orWhereHas(
  //           'categories',
  //           fn($sub) =>
  //           $sub->where('categories.name', 'like', "%{$keyword}%")
  //         )
  //         ->orWhereHas(
  //           'products',
  //           fn($sub) =>
  //           $sub->where('name', 'like', "%{$keyword}%")
  //         );
  //     });
  //   }

  //   // ===== 排序：手動 is_open優先，其次 schedule 開店 =====
  //   $query->orderBy('is_open', 'desc')
  //     ->orderBy('is_open_by_schedule', 'desc');

  //   $shops = $query->get();

  //   // ===== 收斂 is_open：手動開店 OR 排程開店（1 → 開、0 → 關） =====
  //   $shops->transform(function ($shop) {
  //     $shop->is_open = ($shop->is_open == 1 && $shop->is_open_by_schedule > 0) ? 1 : 0;

  //     unset($shop->is_open_by_schedule); // 不回傳給前端（較乾淨）

  //     return $shop;
  //   });

  //   return $this->success('取得商家列表成功', $shops);
  // }


  public function index(Request $request)
  {
    $request->validate([
      'city' => 'nullable|string|max:50',
      'area' => 'nullable|string|max:50',
      'category' => 'nullable|integer',
      'keyword' => 'nullable|string|max:100',
      'lat'  => 'nullable|numeric|between:-90,90',
      'lng'  => 'nullable|numeric|between:-180,180',
    ]);

    $city     = $request->input('city');
    $area     = $request->input('area');
    $category = $request->input('category');
    $keyword  = $request->input('keyword');
    $userLat  = $request->input('lat');
    $userLng  = $request->input('lng');

    // ===== 時間計算 =====
    $now = now();
    // 現在時間換算成「一週內的絕對分鐘」
    // 假設星期一 = 0，星期日 = 6
    $currentWeekIndex = $now->dayOfWeekIso - 1; // dayOfWeekIso: 1=Mon ... 7=Sun
    $currentMinutes = $currentWeekIndex * 1440 + $now->hour * 60 + $now->minute;

    // ===== Shop Query =====
    $query = Shop::query()
      ->with(['categories'])
      ->select('shops.*')
      ->selectRaw("
            COALESCE((
                SELECT COUNT(*)
                FROM schedules
                WHERE schedules.shop_id = shops.id
                AND ? BETWEEN schedules.start_time AND schedules.end_time
            ), 0) AS is_open_by_schedule
        ", [$currentMinutes]);

    // ===== 距離篩選 =====
    if ($userLat && $userLng) {
      $maxKm = env('DELIVERY_MAX_KM', 10);

      $latRange = $maxKm / 111;
      $lngRange = $maxKm / (111 * cos(deg2rad($userLat)));

      $query->whereBetween('lat', [$userLat - $latRange, $userLat + $latRange])
        ->whereBetween('lng', [$userLng - $lngRange, $userLng + $lngRange])
        ->selectRaw("
                (
                    6371 * acos(
                        cos(radians(?)) *
                        cos(radians(shops.lat)) *
                        cos(radians(shops.lng) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(shops.lat))
                    )
                ) AS distance
            ", [$userLat, $userLng, $userLat])
        ->havingRaw('distance <= delivery_km')
        ->orderBy('distance', 'asc');
    } else {
      if ($city) $query->where('city', 'like', "%{$city}%");
      if ($area) $query->where('area', 'like', "%{$area}%");
    }

    // ===== 類別篩選 =====
    if ($category) {
      $query->whereHas('categories', function ($q) use ($category) {
        $q->where('categories.id', $category)
          ->orWhere('categories.name', 'like', "%{$category}%");
      });
    }

    // ===== 關鍵字篩選 =====
    if ($keyword) {
      $query->where(function ($q) use ($keyword) {
        $q->where('brand', 'like', "%{$keyword}%")
          ->orWhere('branch', 'like', "%{$keyword}%")
          ->orWhereHas(
            'categories',
            fn($sub) => $sub->where('categories.name', 'like', "%{$keyword}%")
          )
          ->orWhereHas(
            'products',
            fn($sub) => $sub->where('name', 'like', "%{$keyword}%")
          );
      });
    }

    // ===== 排序：手動 is_open優先，其次 schedule 開店 =====
    $query->orderBy('is_open', 'desc')
      ->orderBy('is_open_by_schedule', 'desc');

    $shops = $query->get();

    // ===== 收斂 is_open：手動開店 OR 排程開店（1 → 開、0 → 關） =====
    $shops->transform(function ($shop) {
      $shop->is_open = ($shop->is_open == 1 && $shop->is_open_by_schedule > 0);
      unset($shop->is_open_by_schedule); // 不回傳給前端
      return $shop;
    });

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
