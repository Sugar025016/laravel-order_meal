<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  public function register(Request $request)
  {
    $request->validate([
      'name' => 'required|string',
      'email' => 'required|email|unique:users',
      'password' => 'required|string|min:6|confirmed',
      'phone' => 'required|digits:10|starts_with:09',
    ]);

    $user = \App\Models\User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => bcrypt($request->password),
      'phone' => $request->phone,
    ]);
    return $this->success('註冊成功', ['user' => $user], 201);
  }

  // AuthController.php
  public function login(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);


    if (!Auth::attempt($request->only('email', 'password'))) {
      return $this->error('登入失敗', ['error' => '無效的帳號或密碼'], 401);
    }

    $user = Auth::user();
    $token = $user->createToken('api-token')->plainTextToken;
    return $this->success('登入成功', ['token' => $token]);
  }

  // 登出
  public function logout(Request $request)
  {
    $request->user()->currentAccessToken()->delete();
    return $this->success('已登出');
  }

  // 取得目前登入者
  public function user(Request $request)
  {
    // return response()->json($request->user());
    $user = $request->user()->load('currentAddress');
    // 只取喜愛商店的 id 陣列
    $favoriteShopIds = $user->favoriteShops()->pluck('shops.id');

    // 你可以把這個加到 user 回傳資料裡
    $user->favoriteShopIds = $favoriteShopIds;
    $user->cartShopCount = $user->getCartShopCountAttribute();
    // $user = $request->user()->load('currentAddress')->load('currentAddress.address_data'); // 同時抓取 addresses 關聯
    return $this->success('取得使用者資料',  $user);
  }

  // 更新個人資料
  public function updateProfile(Request $request)
  {
    $user = $request->user();

    $request->validate([
      'name' => 'required|string|max:50',
    ]);

    $user->update([
      'name' => $request->name,
    ]);

    return $this->success('資料已更新', $user);
  }

  // 修改密碼
  public function changePassword(Request $request)
  {
    $request->validate([
      'current_password' => 'required',
      'new_password' => 'required|string|min:6|confirmed',
    ]);

    if (!Hash::check($request->current_password, $request->user()->password)) {
      return $this->error('密碼錯誤', [], 403);
    }

    $request->user()->update([
      'password' => Hash::make($request->new_password),
    ]);

    return $this->success('密碼已更新');
  }

  public function currentAddress(Request $request, $id)
  {
    // 驗證該地址是否屬於當前使用者
    $user = $request->user();

    // 檢查這個 address 是否屬於該 user
    $address = $user->addrs()->where('id', $id)->first();
    if (!$address) {
      return $this->error('Address 不存在', [], 422);
    }

    // 更新使用者的 current_address_id
    $user->current_address_id = $id;
    $user->save();

    return $this->success('已設定目前外送地址', $address);
  }
}
