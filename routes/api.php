<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TabController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\CartController;
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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/user', [AuthController::class, 'user']);
  Route::post('/logout', [AuthController::class, 'logout']);
  Route::put('/profile/update', [AuthController::class, 'updateProfile']);
  Route::put('/password/change', [AuthController::class, 'changePassword']);
  Route::post('/currentAddress/{id}', [AuthController::class, 'currentAddress']);
});

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/addrs', [AddressController::class, 'index']);   // 取得列表
  Route::post('/addrs', [AddressController::class, 'store']);  // 新增
  // Route::get('/addrs/{id}', [AddressController::class, 'show']); // 單筆
  Route::put('/addrs/{id}', [AddressController::class, 'update']); // 更新
  Route::delete('/addrs/{id}', [AddressController::class, 'destroy']); // 刪除
});


Route::get('/shop', [ShopController::class, 'index']);   // 取得列表
Route::get('/shop/{id}', [ShopController::class, 'show']); // 單筆
Route::middleware('auth:sanctum')->group(function () {
  Route::post('/shop', [ShopController::class, 'store']);  // 新增
  Route::put('/shop/{id}', [ShopController::class, 'update']); // 更新
  Route::delete('/shop/{id}', [ShopController::class, 'destroy']); // 刪除
  Route::post('/shops/{shopId}/categories', [ShopController::class, 'assignToShop']);
});


Route::get('/category', [CategoryController::class, 'index']);   // 取得列表
Route::middleware('auth:sanctum')->group(function () {
  Route::post('/category', [CategoryController::class, 'store']);  // 新增
  Route::get('/category/{id}', [CategoryController::class, 'show']); // 單筆
  Route::delete('/category/{id}', [CategoryController::class, 'destroy']); // 刪除
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
  Route::get('/carts', [CartController::class, 'index']);        // 取得使用者購物車列表
  Route::get('/carts/{id}', [CartController::class, 'show']);    // 單筆購物車
  Route::post('/carts', [CartController::class, 'store']);       // 新增商品到購物車
  Route::put('/carts/{id}', [CartController::class, 'update']);  // 更新購物車商品
  Route::delete('/carts/{id}', [CartController::class, 'destroy']); // 刪除購物車商品
});
