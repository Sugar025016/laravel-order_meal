<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Services\OtpService;
use App\Models\User;

class UserController extends Controller
{


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
    return $this->success('取得使用者資料',  $user);
  }

  // 更新個人資料
  // public function updateProfile(Request $request)
  // {
  //   $user = $request->user();

  //   $request->validate([
  //     'name' => 'required|string|max:50',
  //   ]);

  //   $user->update([
  //     'name' => $request->name,
  //   ]);

  //   return $this->success('資料已更新', $user);
  // }

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

  public function updateName(Request $request)
  {
    $request->validate(['new_name' => 'required|string|max:50']);
    $user = Auth::user();

    // 檢查 flag
    $verified = Cache::get("user:{$user->id}:verified_for_name_change", false);
    if (!$verified) {
      return $this->error('請先驗證密碼', [], 403);
    }

    $user->name = $request->new_name;
    $user->save();

    // 刪除 flag
    Cache::forget("user:{$user->id}:verified_for_name_change");

    return $this->success('名稱已更新', $user);
  }
}
