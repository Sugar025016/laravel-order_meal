<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TabController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartShopController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FavoriteShopController;
use App\Models\ShopFile;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/captcha', [CaptchaController::class, 'get']);
Route::post('/captcha/verify', [CaptchaController::class, 'verify']); //測試用


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//   return $request->user();
// });

Route::post('/register', [AuthController::class, 'register']);
Route::post('/verifyOtp', [AuthController::class, 'verifyOtp']);
Route::post('/sendOtp', [AuthController::class, 'sendOtp']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
  Route::post('/logout', [AuthController::class, 'logout']);
  Route::put('/password/change', [AuthController::class, 'changePassword']);
  Route::post('/verifyPassword', [AuthController::class, 'verifyPassword']);
});

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/user', [UserController::class, 'user']);
  // Route::put('/profile/update', [UserController::class, 'updateProfile']);
  Route::post('/currentAddress/{id}', [UserController::class, 'currentAddress']);
  Route::put('/user/name', [UserController::class, 'updateName']);
});

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/addresses', [AddressController::class, 'index']);   // 取得列表
  Route::post('/addresses', [AddressController::class, 'store']);  // 新增
  Route::put('/addresses/{id}', [AddressController::class, 'update']); // 更新
  Route::delete('/addresses/{id}', [AddressController::class, 'destroy']); // 刪除

  // 設定目前外送地址
  Route::patch('/addresses/{id}/current', [AddressController::class, 'setCurrent']);
});


Route::get('/shop', [ShopController::class, 'index']);   // 取得列表
Route::get('/shop/{id}', [ShopController::class, 'show']); // 單筆

Route::middleware('auth:sanctum')->group(function () {
  Route::post('/shop', [ShopController::class, 'store']);  // 新增
  Route::put('/shop/{id}', [ShopController::class, 'update']); // 更新
  Route::delete('/shop/{id}', [ShopController::class, 'destroy']); // 刪除
  Route::post('/shops/{shopId}/categories', [ShopController::class, 'assignToShop']);
});


Route::get('/category', 'App\Http\Controllers\CategoryController@index');   // 取得列表
Route::middleware('auth:sanctum')->group(function () {
  Route::post('/category', 'App\Http\Controllers\CategoryController@store');  // 新增
  Route::get('/category/{id}', 'App\Http\Controllers\CategoryController@show'); // 單筆
  Route::delete('/category/{id}', 'App\Http\Controllers\CategoryController@destroy'); // 刪除
});




Route::get('/shop/{shopId}/products', [ProductController::class, 'index']);   // 取得列表
Route::get('/shop/{shopId}/products/{id}', [ProductController::class, 'show']); // 單筆
Route::middleware('auth:sanctum')->group(function () {
  Route::post('/shop/{shopId}/products', [ProductController::class, 'store']);  // 新增
  Route::put('/shop/{shopId}/products/{id}', [ProductController::class, 'update']); // 更新
  Route::delete('/shop/{shopId}/products/{id}', [ProductController::class, 'destroy']); // 刪除
});




Route::middleware('auth:sanctum')->group(function () {
  Route::get('/shop/{shopId}/tabs', [TabController::class, 'index']);   // 取得列表
  Route::get('/shop/{shopId}/tabs/{id}', [TabController::class, 'show']); // 單筆
  Route::post('/shop/{shopId}/tabs', [TabController::class, 'store']);  // 新增
  Route::put('/shop/{shopId}/tabs/{id}', [TabController::class, 'update']); // 更新
  Route::delete('/shop/{shopId}/tabs/{id}', [TabController::class, 'destroy']); // 刪除
});
// Route::apiResource('tabs', TabController::class);


Route::middleware('auth:sanctum')->group(function () {
  // 取得某店家的所有排程
  Route::get('/shop/{shopId}/schedules', [ScheduleController::class, 'index']);

  // 取得單筆排程
  Route::get('/shop/{shopId}/schedules/{id}', [ScheduleController::class, 'show']);

  // 新增排程
  Route::post('/shop/{shopId}/schedules', [ScheduleController::class, 'store']);

  // 刪除排程
  Route::delete('/shop/{shopId}/schedules/{id}', [ScheduleController::class, 'destroy']);
});


Route::middleware('auth:sanctum')->group(function () {
  Route::get('/carts', [CartShopController::class, 'index']);        // 取得使用者購物車列表
  Route::get('/carts/{id}', [CartShopController::class, 'show']);    // 單筆購物車
  Route::post('/carts', [CartShopController::class, 'store']);       // 新增商品到購物車
  Route::put('/carts/{id}', [CartShopController::class, 'update']);  // 更新購物車商品
  Route::delete('/carts/{id}', [CartShopController::class, 'destroy']); // 刪除購物車商品
  Route::delete('/cartShop/{id}', [CartShopController::class, 'destroyCartShop']); // 刪除購物車Shop商品
});

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/orders', [OrderController::class, 'index']);        // 取得使用者訂單列表
  Route::get('/orders/{id}', [OrderController::class, 'show']);    // 單筆訂單
  Route::post('/orders/{shopId}', [OrderController::class, 'store']);       // 新增訂單
  Route::put('/orders/{id}', [OrderController::class, 'update']);  // 更新訂單
  Route::delete('/orders/{id}', [OrderController::class, 'destroy']); // 刪除訂單
});

Route::middleware('auth:sanctum')->group(function () {
  Route::post('/favorite/{shop}', [FavoriteShopController::class, 'toggle']);
  Route::get('/favorite', [FavoriteShopController::class, 'list']);
});


// Route::middleware('auth:sanctum')->group(function () {
Route::get('/test', function (Request $request) {
  return response()->json([
    'code' => 200,
    'message' => '成功取得資料！',
    'data' => [
      'id' => 1,
      'name' => 'Laravel 測試商品',
      'price' => 99.9,
    ],
  ]);
});

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/testAuth', function (Request $request) {
    return response()->json([
      'code' => 200,
      'message' => '成功取得資料！',
      'data' => [
        'id' => 1,
        'name' => 'Laravel 測試商品',
        'price' => 99.9,
      ],
    ]);
  });
});
